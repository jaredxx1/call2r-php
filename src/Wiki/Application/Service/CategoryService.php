<?php


namespace App\Wiki\Application\Service;

use App\Company\Application\Exception\CompanyNotFoundException;
use App\Company\Domain\Repository\CompanyRepository;
use App\User\Application\Exception\InvalidUserPrivileges;
use App\User\Domain\Entity\User;
use App\Wiki\Application\Query\FindAllCategoriesFromCompanyQuery;
use App\Wiki\Domain\Entity\Category;
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
     * @param User $user
     * @return Category|null
     * @throws CompanyNotFoundException
     * @throws InvalidUserPrivileges
     */
    public function fromCompany(FindAllCategoriesFromCompanyQuery $query, User $user)
    {
        $company = $this->companyRepository->fromId($query->getIdCompany());
        if (is_null($company)) {
            throw new CompanyNotFoundException();
        }

        if ($user->getCompanyId() != $company->getId()) {
            throw new InvalidUserPrivileges();
        }

        return $this->categoryRepository->fromCompany($query->getIdCompany());
    }
}