<?php


namespace App\Company\Domain\Repository;


use App\Company\Domain\Entity\Company;

interface CompanyRepository
{
    public function fromId(int $id);

    public function getAll();

    public function getMother();

    public function create(Company $company);
}