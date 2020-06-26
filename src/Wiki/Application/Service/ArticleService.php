<?php


namespace App\Wiki\Application\Service;


use App\Company\Application\Exception\CompanyNotFoundException;
use App\Company\Domain\Repository\CompanyRepository;
use App\Wiki\Application\Command\CreateArticleCommand;
use App\Wiki\Application\Command\UpdateArticleCommand;
use App\Wiki\Application\Exception\ArticleNotAvalible;
use App\Wiki\Application\Exception\ArticleNotFoundException;
use App\Wiki\Application\Query\DeleteArticleCommand;
use App\Wiki\Application\Query\FindAllArticlesFromCompanyQuery;
use App\Wiki\Application\Query\FindArticlesByIdQuery;
use App\Wiki\Domain\Entity\Article;
use App\Wiki\Domain\Entity\Category;
use App\Wiki\Domain\Repository\ArticleRepository;
use App\Wiki\Domain\Repository\CategoryRepository;

/**
 * Class ArticleService
 * @package App\Wiki\Application\Service
 */
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
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * ArticleService constructor.
     * @param ArticleRepository $articleRepository
     * @param CompanyRepository $companyRepository
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(ArticleRepository $articleRepository, CompanyRepository $companyRepository, CategoryRepository $categoryRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->companyRepository = $companyRepository;
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * @param FindAllArticlesFromCompanyQuery $query
     * @return mixed
     */
    public function fromCompany(FindAllArticlesFromCompanyQuery $query)
    {
        return $this->articleRepository->fromCompany($query->id());
    }

    /**
     * @param CreateArticleCommand $command
     * @return Article|null
     * @throws CompanyNotFoundException
     */
    public function create(CreateArticleCommand $command)
    {
        $company = $this->companyRepository->fromId($command->idCompany());
        if (is_null($company)) {
            throw new CompanyNotFoundException();
        }

        foreach ($command->categories() as $category) {
            $foundCategory = $this->categoryRepository->fromCompanyTitle($category['title'], $category['idCompany']);
            if (is_null($foundCategory)) {
                $categories[] = new Category(
                    null,
                    $category['idCompany'],
                    $category['title'],
                    $category['active']
                );
            } else {
                $categories[] = $foundCategory;
            }
        }

        $article = new Article(
            null,
            $command->idCompany(),
            $command->title(),
            $command->description(),
            $categories
        );

        $article = $this->articleRepository->create($article);

        return $article;
    }

    /**
     * @param UpdateArticleCommand $command
     * @return Article|null
     * @throws ArticleNotFoundException|CompanyNotFoundException
     */
    public function update(UpdateArticleCommand $command)
    {
        //validate if article exists
        $article = $this->articleRepository->fromId($command->id());
        if (is_null($article)) {
            throw new ArticleNotFoundException();
        }

        foreach ($command->categories() as $category) {
            //if company exists
            $company = $this->companyRepository->fromId($category['idCompany']);
            if (is_null($company)) {
                throw new CompanyNotFoundException();
            }
            //select from base the category
            $foundCategory = $this->categoryRepository->fromCompanyTitle($category['title'], $category['idCompany']);
            if (is_null($foundCategory)) {
                $categories[] = new Category(
                    null,
                    $category['idCompany'],
                    $category['title'],
                    $category['active']
                );
            } else {
                //if it already exists
                $categories[] = $foundCategory;
            }
        }

        $article->setDescription($command->description());
        $article->setTitle($command->title());
        $article->setCategories($categories);

        return $this->articleRepository->update($article);
    }

    /**
     * @param FindArticlesByIdQuery $query
     * @return Article
     * @throws ArticleNotFoundException|CompanyNotFoundException|ArticleNotAvalible
     */
    public function fromArticle(FindArticlesByIdQuery $query)
    {
        $idArticle = $query->idArticle();
        $idCompany = $query->idCompany();

        $company = $this->companyRepository->fromId($idCompany);

        if (is_null($company)) {
            throw new CompanyNotFoundException();
        }

        $article = $this->articleRepository->fromId($idArticle);

        if (is_null($article)) {
            throw new ArticleNotFoundException();
        }

        if($article->idCompany() != $company->id()){
            throw new ArticleNotAvalible();
        }

        return $article;
    }

    /**
     * @param DeleteArticleCommand $query
     * @throws ArticleNotFoundException
     */
    public function delete(DeleteArticleCommand $query)
    {
        $id = $query->id();
        $article = $this->articleRepository->fromId($id);
        if (is_null($article)) {
            throw new ArticleNotFoundException();
        }
        $this->articleRepository->delete($article);
    }

}