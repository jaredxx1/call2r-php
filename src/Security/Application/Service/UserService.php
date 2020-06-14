<?php


namespace App\Security\Application\Service;


use App\Security\Application\Command\LoginCommand;
use App\Security\Application\Exception\InvalidCredentialsException;
use App\Security\Domain\Entity\User;
use App\Security\Domain\Repository\UserRepository;
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
}