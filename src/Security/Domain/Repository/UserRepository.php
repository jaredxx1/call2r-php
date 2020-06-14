<?php


namespace App\Security\Domain\Repository;


use App\Security\Domain\Entity\User;

interface UserRepository
{
    public function fromCpf(string $cpf): ?User;
}