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
     * ArticleService constructor.
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }


    /**
     * @param FindAllWikiFromCompanyQuery $query
     * @return mixed
     */
    public function fromCompany(FindAllWikiFromCompanyQuery $query)
    {
        return $this->articleRepository->fromCompany($query->id());
    }

}