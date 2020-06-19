<?php


namespace App\Security\Domain\Repository;


use App\Security\Domain\Entity\User;

interface UserRepository
{
    public function fromId(int $id): ?User;

    public function fromCpf(string $cpf): ?User;

    public function fromLoginCredentials(string $cpf, string $password): ?User;

    public function findSupportUsers(): array;

    public function findManagers(): array;
}