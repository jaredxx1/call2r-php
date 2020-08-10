<?php


namespace App\User\Domain\Repository;


use App\User\Domain\Entity\User;

/**
 * Interface UserRepository
 * @package App\User\Domain\Repository
 */
interface UserRepository
{
    /**
     * @param int $id
     * @return User|null
     */
    public function fromId(int $id): ?User;

    /**
     * @param string $cpf
     * @return User|null
     */
    public function fromCpf(string $cpf): ?User;

    /**
     * @param string $cpf
     * @param string $password
     * @return User|null
     */
    public function fromLoginCredentials(string $cpf, string $password): ?User;

    /**
     * @param User $user
     * @return array
     */
    public function findSupportUsers(User $user): array;

    /**
     * @param User $user
     * @return array
     */
    public function findManagers(User $user): array;

    /**
     * @param User $user
     * @return array
     */
    public function findAdmins(User $user): array;

    /**
     * @param User $user
     * @return array
     */
    public function findClientUsers(User $user): array;

    /**
     * @param User $user
     * @return User|null
     */
    public function createUser(User $user): ?User;

    /**
     * @param User $user
     * @return User
     */
    public function updateUser(User $user): User;

    /**
     * @param string $cpf
     * @param $birthdate
     * @return User|null
     */
    public function fromCpfBirthdate(string $cpf, $birthdate): ?User;

}