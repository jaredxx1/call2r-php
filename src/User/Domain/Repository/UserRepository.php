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
     * @param string $email
     * @return User|null
     */
    public function fromEmail(string $email): ?User;


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

    /**
     * @return mixed
     */
    public function findClientManagers();

    /**
     * @return mixed
     */
    public function findSupportsManagers();

    /**
     * @return mixed
     */
    public function findClient();

    /**
     * @param int $companyId
     * @return mixed
     */
    public function findSupport(int $companyId);

}