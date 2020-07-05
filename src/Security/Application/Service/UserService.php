<?php


namespace App\Security\Application\Service;


use App\Core\Infrastructure\Storaged\AWS\S3;
use App\Security\Application\Command\CreateUserCommand;
use App\Security\Application\Command\LoginCommand;
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
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param S3 $s3
     */
    public function __construct(UserRepository $userRepository, S3 $s3)
    {
        $this->userRepository = $userRepository;
        $this->s3 = $s3;
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
        $user = $this->userRepository->fromLoginCredentials(
            $command->getCpf(),
            $command->getPassword()
        );

        if (is_null($user)) {
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
        $user = new User(
            null,
            $command->getCpf(),
            $command->getPassword(),
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
     * @param User $user
     * @param $image
     * @return User
     * @throws Exception
     */
    public function updateImage(User $user, $image)
    {
        $uuid = Uuid::uuid4();
        $url = null;
        if(!is_null($image) && preg_match('/image\//', $image->getMimeType())){
            $url = $this->s3->sendFile('user',$uuid->serialize(),$image);
        }
        $user->setImage($url);
        return $this->userRepository->updateUser($user);
    }
}