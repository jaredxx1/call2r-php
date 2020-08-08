<?php


namespace App\Wiki\Application\Service;

use App\Company\Domain\Repository\CompanyRepository;
use App\Wiki\Application\Query\FindAllCategoriesFromCompanyQuery;
use App\Wiki\Domain\Repository\CategoryRepository;

class CategoryService
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * CategoryService constructor.
     * @param CategoryRepository $categoryRepository
     * @param CompanyRepository $companyRepository
     */
    public function __construct(CategoryRepository $categoryRepository, CompanyRepository $companyRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param FindAllCategoriesFromCompanyQuery $query
     * @return \App\Wiki\Domain\Entity\Category|null
     */
    public function fromCompany(FindAllCategoriesFromCompanyQuery $query)
    {
        return $this->categoryRepository->fromCompany($query->getId());
    }
}