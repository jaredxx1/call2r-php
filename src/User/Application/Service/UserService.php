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
use App\User\Application\Exception\DuplicateCpfException;
use App\User\Application\Exception\InvalidCredentialsException;
use App\User\Application\Exception\InvalidRegisterInMotherCompany;
use App\User\Application\Exception\InvalidUserPrivileges;
use App\User\Application\Exception\UserNotFoundException;
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
            "exp" => $now_seconds + (60 * 60),
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

        if(is_null($company)){
            throw new CompanyNotFoundException();
        }

        if(!is_null($this->userRepository->fromCpf($command->getCpf()))){
            throw new DuplicateCpfException();
        }

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
     * @param UpdateUserCommand $command
     * @param User $user
     * @return User
     * @throws InvalidUserPrivileges
     * @throws UserNotFoundException
     */
    public function updateUser(UpdateUserCommand $command, User $user): User
    {
        $user = $this->fromId($command->getId(),$user);

        if (!is_null($command->getNewPassword())
            && !is_null($command->getOldPassword())
            && password_verify($command->getOldPassword(), $user->getPassword())) {
            $newHashedPassword = password_hash($command->getNewPassword(), PASSWORD_BCRYPT);
            $user->setPassword($newHashedPassword);
        }

        if (!is_null($command->getName())) {
            $user->setName($command->getName());
        }

        if (!is_null($command->getEmail())) {
            $user->setEmail($command->getEmail());
        }

        if (!is_null($command->getRole())) {
            $user->setRole($command->getRole());
        }

        if (!is_null($command->getActive())) {
            $user->setActive($command->getActive());
        }

        return $this->userRepository->updateUser($user);
    }

    /**
     * @param string $id
     * @param User $requestUser
     * @return User|null
     * @throws InvalidUserPrivileges
     * @throws UserNotFoundException
     */
    public function fromId(string $id, User $requestUser): ?User
    {
        $user = $this->userRepository->fromId($id);

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        if (!self::validateUserRole($user, $requestUser)) {
            throw new InvalidUserPrivileges();
        }

        return $user;
    }

    private static function validateUserRole(?User $user, User $requestUser)
    {
        if ($user->getId() == $requestUser->getId()) {
            return true;
        }
        if ($requestUser->getRole() == 'ROLE_MANAGER') {
            return (($user->getRole() == 'ROLE_USER' || $user->getRole() == 'ROLE_CLIENT')
                && ($user->getCompanyId() == $requestUser->getCompanyId()));
        }

        return false;
    }

    /**
     * @param UpdateUserImageCommand $command
     * @return User
     * @throws Exception
     */
    public function updateImage(UpdateUserImageCommand $command)
    {
        $uuid = Uuid::uuid4();

        $user = $command->getUser();

        $uploadedFile = $command->getUploadFile();

        $path = $uploadedFile->getPathname();
        $name = $uploadedFile->getClientOriginalName();
        $contentType = $uploadedFile->getMimeType();

        $url = $this->s3->sendFile('user', $uuid->serialize(), $path, $name, $contentType);

        $user->setImage($url);

        return $this->userRepository->updateUser($user);
    }

    /**
     * @param ResetPasswordCommand $command
     * @return array
     * @throws EmailSendException
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws Exception
     */
    public function resetPassword(ResetPasswordCommand $command)
    {
        $birthdate = (new DateTime($command->getBirthdate()))->format('Y-m-d');
        $user = $this->userRepository->fromCpf($command->getCpf());
        $userBirhtdate = $user->getBirthdate()->format('Y-m-d');
        if($userBirhtdate != $birthdate){
            return [];
        }

        $newPassword = substr(sha1(time()), 0, 6);

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $user->setPassword($hashedPassword);

        $this->userRepository->updateUser($user);

        $this->emailService->sendEmail($user->getEmail(), 'nome', 'Reset Password', '<H1>Your new password is ->' . $newPassword . '</H1>');

    }
}

