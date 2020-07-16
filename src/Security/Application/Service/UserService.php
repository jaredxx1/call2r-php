<?php


namespace App\Security\Application\Service;


use App\Core\Infrastructure\Container\Application\Exception\EmailSendException;
use App\Core\Infrastructure\Storaged\AWS\S3;
use App\Core\Infrastructure\Email\EmailService;
use App\Security\Application\Command\CreateUserCommand;
use App\Security\Application\Command\LoginCommand;
use App\Security\Application\Command\UpdateUserImageCommand;
use App\Security\Application\Command\ResetPasswordCommand;
use App\Security\Application\Command\UpdateUserCommand;
use App\Security\Application\Exception\InvalidCredentialsException;
use App\Security\Application\Exception\UserNotFoundException;
use App\Security\Application\Query\FindUsersByRoleQuery;
use App\Security\Domain\Entity\User;
use App\Security\Domain\Repository\UserRepository;
use Exception;
use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UserService
 * @package App\Security\Application\Service
 */
final class UserService
{

    /**
     * @var UserRepository
     */
    private $userRepository;

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
     * @param S3 $s3
     * @param EmailService $emailService
     */
    public function __construct(UserRepository $userRepository, S3 $s3, EmailService $emailService)
    {
        $this->userRepository = $userRepository;
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
            'cpf' => $user->getCpf(),
            'roles' => $user->getRoles(),
        ];

        return JWT::encode($payload, $_ENV['JWT_SECRET']);
    }

    /**
     * @param FindUsersByRoleQuery $query
     * @return array
     * @throws Exception
     */
    public function findUsersByRole(FindUsersByRoleQuery $query)
    {
        switch ($query->getRole()) {
            case 'manager':
                $users = $this->userRepository->findManagers();
                break;
            case 'support':
                $users = $this->userRepository->findSupportUsers();
                break;
            default:
                throw new Exception('Unexpected role');
        }

        return $users;
    }

    /**
     *
     * @param CreateUserCommand $command
     * @return User|null
     */
    public function createUser(CreateUserCommand $command)
    {
        $hashedPassword = password_hash($command->getPassword(), PASSWORD_BCRYPT);

        $user = new User(
            null,
            $command->getCpf(),
            $hashedPassword,
            $command->getRole(),
            $command->getEmail(),
            null,
            $command->getBirthdate(),
            $command->isActive(),
            $command->getCompanyId()
        );

        return $this->userRepository->createUser($user);
    }

    /**
     * @param UpdateUserCommand $command
     * @return User
     * @throws UserNotFoundException
     */
    public function updateUser(UpdateUserCommand $command): User
    {
        $user = $this->fromId($command->getId());

        if (!is_null($command->getPassword())) {
            $user->setPassword($command->getPassword());
        }

        if (!is_null($command->getEmail())) {
            $user->setEmail($command->getEmail());
        }

        if (!is_null($command->getRole())) {
            $user->setRole($command->getRole());
        }

        if (!is_null($command->isActive())) {
            $user->setActive($command->isActive());
        }

        return $this->userRepository->updateUser($user);
    }

    /**
     * @param string $id
     * @return User|null
     * @throws UserNotFoundException
     */
    public function fromId(string $id): ?User
    {
        $user = $this->userRepository->fromId($id);

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        return $user;
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

        $url = $this->s3->sendFile('user',$uuid->serialize(), $path, $name ,$contentType);

        $user->setImage($url);

        return $this->userRepository->updateUser($user);
    }
  
    /**
     * @param ResetPasswordCommand $command
     * @throws UserNotFoundException
     * @throws EmailSendException
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function resetPassword(ResetPasswordCommand $command){

        $user = $this->userRepository->fromCpfBirthdate($command->getCpf(), $command->getBirthdate());

        if(is_null($user)){
            throw new UserNotFoundException();
        }

        $this->emailService->sendEmail($user->getEmail(),'nome','Reset Password','<H1>Your new password</H1>');
    }
}