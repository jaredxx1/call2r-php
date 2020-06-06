<?php


namespace App\Company\Domain\Repository;


use App\Company\Domain\Entity\Company;

interface CompanyRepository
{
    public function fromId(int $id): Company;

    public function getAll();

    public function getMother();

    public function create(Company $company);

    public function update(Company $company);
}