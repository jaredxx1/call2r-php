<?php


namespace App\Wiki\Domain\Repository;


use App\Company\Domain\Entity\Company;

interface ArticleRepository
{
    public function getAll(Company $company);
}