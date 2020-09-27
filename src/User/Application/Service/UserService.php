<?php


namespace App\User\Application\Service;


use App\Company\Application\Exception\CompanyNotFoundException;
use App\Company\Domain\Repository\CompanyRepository;
use App\Core\Infrastructure\Container\Application\Exception\EmailSendException;
use App\Core\Infrastructure\Email\EmailService;
use App\Core\Infrastructure\Storaged\AWS\S3;
use App\User\Application\Command\CreateUserCommand;
use App\User\Application\Command\LoginCommand;
use App\User\Application\Command\ResetPasswordCommand;
use App\User\Application\Command\UpdateUserCommand;
use App\User\Application\Command\UpdateUserImageCommand;
use App\User\Application\Exception\CreateUserException;
use App\User\Application\Exception\DuplicateCpfException;
use App\User\Application\Exception\DuplicateEmailException;
use App\User\Application\Exception\FromIdException;
use App\User\Application\Exception\InvalidCredentialsException;
use App\User\Application\Exception\ResetPasswordException;
use App\User\Application\Exception\UpdateImageException;
use App\User\Application\Exception\UpdateUserException;
use App\User\Application\Exception\UserNotFoundException;
use App\User\Application\Exception\WrongPasswordException;
use App\User\Application\Query\FindUsersByRoleQuery;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepository;
use DateTime;
use Exception;
use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;

/**
 * Class UserService
 * @package App\User\Application\Service
 */
final class UserService
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var S3
     */
    private $s3;

    /**
     * @var EmailService
     */
    private $emailService;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param CompanyRepository $companyRepository
     * @param S3 $s3
     * @param EmailService $emailService
     */
    public function __construct(UserRepository $userRepository, CompanyRepository $companyRepository, S3 $s3, EmailService $emailService)
    {
        $this->userRepository = $userRepository;
        $this->companyRepository = $companyRepository;
        $this->s3 = $s3;
        $this->emailService = $emailService;
    }

    /**
     * @param string $cpf
     * @return User|null
     */
    public function fromCpf(string $cpf): ?User
    {
        return $this->userRepository->fromCpf($cpf);
    }

    /**
     * @param LoginCommand $command
     * @return User|null
     * @throws InvalidCredentialsException
     */
    public function fromLoginCredentials(LoginCommand $command): ?User
    {
        $user = $this->userRepository->fromCpf(
            $command->getCpf()
        );

        if (is_null($user)) {
            throw new InvalidCredentialsException();
        }

        $isPasswordValid = password_verify($command->getPassword(), $user->getPassword());

        if (!$isPasswordValid) {
            throw new InvalidCredentialsException();
        }

        return $user;
    }

    /**
     * @param User $user
     * @return string
     */
    public function generateAuthToken(User $user)
    {
        $now_seconds = time();

        $payload = [
            "iat" => $now_seconds,
            "exp" => $now_seconds + (8 * 60 * 60),
            "user_id" => $user->getId(),
            'name' => $user->getName(),
            'user_company' => $user->getCompanyId(),
            'cpf' => $user->getCpf(),
            'roles' => $user->getRoles(),
        ];

        return JWT::encode($payload, $_ENV['JWT_SECRET']);
    }

    /**
     * @param User $user
     * @param FindUsersByRoleQuery $query
     * @return array
     * @throws Exception
     */
    public function findUsersByRole(User $user, FindUsersByRoleQuery $query)
    {
        switch ($query->getRole()) {
            case 'manager-client':
                $users = $this->userRepository->findClientManagers();
                break;
            case 'manager-support':
                $users = $this->userRepository->findSupportsManagers();
                break;
            case 'client':
                $users = $this->userRepository->findClient();
                break;
            case 'support':
                $users = $this->userRepository->findSupport($user->getCompanyId());
                break;
            default:
                throw new Exception('Unexpected role');
        }

        return $users;
    }

    /**
     * @param CreateUserCommand $command
     * @param User $user
     * @return User|null
     * @throws Exception
     */
    public function createUser(CreateUserCommand $command, User $user)
    {
        $company = $this->companyRepository->fromId($command->getCompanyId());

        if (is_null($company)) {
            throw new CompanyNotFoundException();
        }

        if (!is_null($this->userRepository->fromCpf($command->getCpf()))) {
            throw new DuplicateCpfException();
        }

        if (!is_null($this->userRepository->fromEmail($command->getEmail()))) {
            throw new DuplicateEmailException();
        }

        $this->validateCreateUser($command, $user);

        $hashedPassword = password_hash($command->getPassword(), PASSWORD_BCRYPT);

        $user = new User(
            null,
            $command->getName(),
            $command->getCpf(),
            $hashedPassword,
            $command->getRole(),
            $command->getEmail(),
            null,
            new DateTime($command->getBirthdate()),
            $command->isActive(),
            $command->getCompanyId()
        );

        return $this->userRepository->createUser($user);
    }

    /**
     * @param CreateUserCommand $command
     * @param User $user
     * @throws CreateUserException
     * @throws Exception
     */
    private function validateCreateUser(CreateUserCommand $command, User $user)
    {
        switch ($user->getRole()) {
            case User::client:
                throw new CreateUserException();
            case User::support:
                throw new CreateUserException();
            case User::managerSupport:
                if (
                !(($user->getCompanyId() == $command->getCompanyId()) &&
                    ($command->getRole() == User::support))
                ) {
                    throw new CreateUserException();
                }
                break;
            case User::managerClient:
                if (
                !(($user->getCompanyId() == $command->getCompanyId()) &&
                    ($command->getRole() == User::client))
                ) {
                    throw new CreateUserException();
                }
                break;
            case User::admin:
                if (
                !(($command->getRole() == User::managerSupport) || ($command->getRole() == User::managerClient))
                ) {
                    throw new CreateUserException();
                }
                break;
            default:
                throw new Exception('Unexpected role');
        }
    }

    /**
     * @param UpdateUserCommand $command
     * @param User $userSession
     * @return User
     * @throws UpdateUserException
     * @throws UserNotFoundException
     * @throws Exception
     */
    public function updateUser(UpdateUserCommand $command, User $userSession): User
    {
        $user = $this->userRepository->fromId($command->getId());

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        if ($user->getId() == $userSession->getId()) {
            $user = $this->validateSelfUpdate($command, $user);
        }

        if ($user->getId() != $userSession->getId()) {
            $user = $this->validateAnotherUserUpdate($command, $user, $userSession);
        }

        if (!is_null($command->getName())) {
            $user->setName($command->getName());
        }

        if (!is_null($command->getEmail())) {
            $userEmail = $this->userRepository->fromEmail($command->getEmail());
            $this->validateEmailUpdate($userEmail, $user);
            $user->setEmail($command->getEmail());
        }

        return $this->userRepository->updateUser($user);
    }

    /**
     * @param UpdateUserCommand $command
     * @param User $user
     * @return User
     * @throws WrongPasswordException
     */
    private function validateSelfUpdate(UpdateUserCommand $command, User $user)
    {
        if (!is_null($command->getNewPassword())
            && !is_null($command->getOldPassword())
            && password_verify($command->getOldPassword(), $user->getPassword())) {
            $newHashedPassword = password_hash($command->getNewPassword(), PASSWORD_BCRYPT);
            $user->setPassword($newHashedPassword);
        }

        if (!is_null($command->getNewPassword())
            && !is_null($command->getOldPassword())
            && !(password_verify($command->getOldPassword(), $user->getPassword()))) {
            throw new WrongPasswordException();
        }

        return $user;
    }

    /**
     * @param UpdateUserCommand $command
     * @param User $user
     * @param User $userSession
     * @return User
     * @throws UpdateUserException
     * @throws Exception
     */
    private function validateAnotherUserUpdate(UpdateUserCommand $command, User $user, User $userSession)
    {
        if (!is_null($command->getActive())) {
            $user->setActive($command->getActive());
            switch ($userSession->getRole()) {
                case User::client:
                    throw new UpdateUserException();
                case User::support:
                    throw new UpdateUserException();
                case User::managerSupport:
                    if (
                    !(($user->getCompanyId() == $userSession->getCompanyId()) &&
                        ($user->getRole() == User::support))
                    ) {
                        throw new UpdateUserException();
                    }
                    break;
                case User::managerClient:
                    if (
                    !(($user->getCompanyId() == $userSession->getCompanyId()) &&
                        ($user->getRole() == User::client))
                    ) {
                        throw new UpdateUserException();
                    }
                    break;
                case User::admin:
                    if (
                    !(($user->getRole() == User::managerSupport) || ($user->getRole() == User::managerClient))
                    ) {
                        throw new UpdateUserException();
                    }
                    break;
                default:
                    throw new Exception('Unexpected role');
            }
        }

        if (!is_null($command->getRole())) {
            $user->setRole($command->getRole());
            switch ($userSession->getRole()) {
                case User::client:
                    throw new UpdateUserException();
                case User::support:
                    throw new UpdateUserException();
                case User::managerSupport:
                    if (
                    !(($user->getCompanyId() == $userSession->getCompanyId()) &&
                        ($user->getRole() == User::managerSupport))
                    ) {
                        throw new UpdateUserException();
                    }
                    break;
                case User::managerClient:
                    if (
                    !(($user->getCompanyId() == $userSession->getCompanyId()) &&
                        ($user->getRole() == User::managerClient))
                    ) {
                        throw new UpdateUserException();
                    }
                    break;
                case User::admin:
                    if (
                    !(($user->getRole() == User::support) || ($user->getRole() == User::client))
                    ) {
                        throw new UpdateUserException();
                    }
                    break;
                default:
                    throw new Exception('Unexpected role');
            }
        }

        return $user;
    }

    /**
     * @param User|null $userEmail
     * @param User $user
     * @throws DuplicateEmailException
     */
    public function validateEmailUpdate(?User $userEmail, User $user): void
    {
        if (!is_null($userEmail)) {
            if ($userEmail->getId() != $user->getId()) {
                throw new DuplicateEmailException();
            }
        }
    }

    /**
     * @param string $id
     * @param User $userSession
     * @return User|null
     * @throws FromIdException
     * @throws UserNotFoundException
     */
    public function fromId(string $id, User $userSession): ?User
    {
        $user = $this->userRepository->fromId($id);

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        if ($user->getId() != $userSession->getId()) {
            $this->validateAnotherUserFromId($user, $userSession);
        }

        return $user;
    }

    /**
     * @param User $user
     * @param User $userSession
     * @throws FromIdException
     * @throws Exception
     */
    private function validateAnotherUserFromId(User $user, User $userSession)
    {
        switch ($userSession->getRole()) {
            case User::client:
                throw new FromIdException();
            case User::support:
                throw new FromIdException();
            case User::managerSupport:
                if (
                !(($user->getCompanyId() == $userSession->getCompanyId()) &&
                    ($user->getRole() == User::support))
                ) {
                    throw new FromIdException();
                }
                break;
            case User::managerClient:
                if (
                !(($user->getCompanyId() == $userSession->getCompanyId()) &&
                    ($user->getRole() == User::client))
                ) {
                    throw new FromIdException();
                }
                break;
            case User::admin:
                if (
                !(($user->getRole() == User::managerSupport) || ($user->getRole() == User::managerClient))
                ) {
                    throw new FromIdException();
                }
                break;
            default:
                throw new Exception('Unexpected role');
        }
    }

    /**
     * @param User $userSession
     * @param UpdateUserImageCommand $command
     * @return User
     * @throws UpdateImageException
     * @throws Exception
     */
    public function updateImage(User $userSession, UpdateUserImageCommand $command)
    {
        $uuid = Uuid::uuid4();


        if ($command->getId() != $userSession->getId()) {
            throw new UpdateImageException();
        }

        $uploadedFile = $command->getUploadFile();

        $path = $uploadedFile->getPathname();
        $name = $uploadedFile->getClientOriginalName();
        $contentType = $uploadedFile->getMimeType();

        $url = $this->s3->sendFile('user', $uuid->serialize(), $path, $name, $contentType);

        $userSession->setImage($url);

        return $this->userRepository->updateUser($userSession);
    }

    /**
     * @param ResetPasswordCommand $command
     * @throws EmailSendException
     * @throws ResetPasswordException
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws Exception
     */
    public function resetPassword(ResetPasswordCommand $command)
    {
        $birthdate = new DateTime($command->getBirthdate());
        $user = $this->userRepository->fromCpf($command->getCpf());
        if ($user->getBirthdate() != $birthdate) {
            throw new ResetPasswordException();
        }

        $newPassword = substr(sha1(time()), 0, 6);

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $this->emailService->sendEmail($user->getEmail(), 'nome', 'Reset Password', '<H1>Your new password is ->' . $newPassword . '</H1>');

        $user->setPassword($hashedPassword);

        $this->userRepository->updateUser($user);
    }
}

