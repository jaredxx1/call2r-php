<?php


namespace App\Company\Domain\Repository;


use App\Company\Domain\Entity\Company;

/**
 * Interface CompanyRepository
 * @package App\Company\Domain\Repository
 */
interface CompanyRepository
{
    /**
     * @param int $id
     * @return Company|null
     */
    public function fromId(int $id): ?Company;

    /**
     * @return mixed
     */
    public function getAll();

    /**
     * @return Company|null
     */
    public function getMother(): ?Company;

    /**
     * @param Company $company
     * @return Company|null
     */
    public function create(Company $company): ?Company;

    /**
     * @param Company $company
     * @return Company|null
     */
    public function update(Company $company): ?Company;
}