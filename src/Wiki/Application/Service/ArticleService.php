<?php


namespace App\Wiki\Application\Service;


use App\Company\Application\Exception\CompanyNotFoundException;
use App\Company\Domain\Repository\CompanyRepository;
use App\Wiki\Application\Query\FindAllWikiFromCompanyQuery;
use App\Wiki\Domain\Repository\ArticleRepository;

class ArticleService
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * ArticleService constructor.
     * @param ArticleRepository $articleRepository
     * @param CompanyRepository $companyRepository
     */
    public function __construct(ArticleRepository $articleRepository, CompanyRepository $companyRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param FindAllWikiFromCompanyQuery $query
     * @return mixed
     */
    public function getAll(FindAllWikiFromCompanyQuery $query)
    {
        $company = $this->companyRepository->fromId($query->id());

        if(is_null($company)){
            throw new CompanyNotFoundException();
        }

        return $this->articleRepository->getAll($company);
    }

}