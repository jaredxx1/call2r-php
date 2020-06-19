<?php


namespace App\Security\Application\Service;


use App\Security\Application\Command\LoginCommand;
use App\Security\Application\Exception\InvalidCredentialsException;
use App\Security\Application\Exception\UserNotFoundException;
use App\Security\Application\Query\FindUsersByRoleQuery;
use App\Security\Domain\Entity\User;
use App\Security\Domain\Repository\UserRepository;
use Exception;
use Firebase\JWT\JWT;

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
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
}