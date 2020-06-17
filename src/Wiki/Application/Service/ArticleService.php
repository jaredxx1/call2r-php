<?php


namespace App\Wiki\Application\Service;


use App\Company\Application\Exception\CompanyNotFoundException;
use App\Company\Domain\Repository\CompanyRepository;
use App\Wiki\Application\Command\CreateArticleCommand;
use App\Wiki\Application\Query\FindAllWikiFromCompanyQuery;
use App\Wiki\Domain\Entity\Article;
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
    public function fromCompany(FindAllWikiFromCompanyQuery $query)
    {
        return $this->articleRepository->fromCompany($query->id());
    }

    public function create(CreateArticleCommand $command){
        $company = $this->companyRepository->fromId($command->idCompany());
        if(is_null($company)){
            throw new CompanyNotFoundException();
        }

        $article = new Article(
            null,
            $command->idCompany(),
            $command->title(),
            $command->description()
        );

        return $this->articleRepository->create($article);
    }

}