<?php


namespace App\Company\Domain\Repository;


use App\Company\Domain\Entity\Company;

interface CompanyRepository
{
    public function fromId(int $id): ?Company;

    public function getAll();

    public function getMother(): ?Company;

    public function create(Company $company): ?Company;

    public function update(Company $company): ?Company;
}