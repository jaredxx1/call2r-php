<?php


namespace App\Wiki\Application\Service;


use App\Company\Application\Exception\CompanyNotFoundException;
use App\Company\Domain\Repository\CompanyRepository;
use App\User\Application\Exception\InvalidUserPrivileges;
use App\User\Domain\Entity\User;
use App\Wiki\Application\Command\CreateArticleCommand;
use App\Wiki\Application\Command\DeleteArticleCommand;
use App\Wiki\Application\Command\UpdateArticleCommand;
use App\Wiki\Application\Exception\ArticleNotFoundException;
use App\Wiki\Application\Query\FindAllArticlesFromCompanyQuery;
use App\Wiki\Application\Query\FindArticlesByIdQuery;
use App\Wiki\Domain\Entity\Article;
use App\Wiki\Domain\Entity\Category;
use App\Wiki\Domain\Repository\ArticleRepository;
use App\Wiki\Domain\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @param User $user
     * @return mixed
     * @throws CompanyNotFoundException
     */
    public function fromCompany(FindAllArticlesFromCompanyQuery $query, User $user)
    {
        $company = $this->companyRepository->fromId($query->getIdCompany());
        if (is_null($company)) {
            throw new CompanyNotFoundException();
        }

        return $this->articleRepository->fromCompany($query->getIdCompany());
    }

    /**
     * @param CreateArticleCommand $command
     * @param User $user
     * @return Article|null
     * @throws CompanyNotFoundException
     */
    public function create(CreateArticleCommand $command, User $user)
    {
        $company = $this->companyRepository->fromId($command->getIdCompany());
        if (is_null($company)) {
            throw new CompanyNotFoundException();
        }

        $categories = $this->createCategoriesObject($command);

        $article = new Article(
            null,
            $command->getIdCompany(),
            $command->getTitle(),
            $command->getDescription(),
            $categories
        );

        $article = $this->articleRepository->create($article);

        return $article;
    }

    /**
     * @param mixed $command
     * @return ArrayCollection
     */
    private function createCategoriesObject($command): ArrayCollection
    {
        $categories = new ArrayCollection();
        $titleOfCategories = new ArrayCollection();
        foreach ($command->getCategories() as $category) {
            $foundCategory = $this->categoryRepository->fromArticleTitle($category['title'], $category['idCompany']);
            if (is_null($foundCategory)) {
                $localCategory = new Category(
                    null,
                    $category['idCompany'],
                    $category['title']
                );
                if (!$titleOfCategories->contains($localCategory->getTitle())) {
                    $categories->add($localCategory);
                    $titleOfCategories->add($localCategory->getTitle());
                }
            } else {
                $categories->add($foundCategory);
            }
        }
        return $categories;
    }

    /**
     * @param UpdateArticleCommand $command
     * @param User $user
     * @return Article|null
     * @throws ArticleNotFoundException
     * @throws CompanyNotFoundException
     */
    public function update(UpdateArticleCommand $command, User $user)
    {
        $company = $this->companyRepository->fromId($command->getIdCompany());
        if (is_null($company)) {
            throw new CompanyNotFoundException();
        }

        $article = $this->articleRepository->fromId($command->getId());
        if (is_null($article)) {
            throw new ArticleNotFoundException();
        }

        if (!is_null($command->getTitle())) {
            $article->setTitle($command->getTitle());
        }

        if (!is_null($command->getDescription())) {
            $article->setDescription($command->getDescription());
        }

        if (!is_null($command->getCategories())) {
            $categories = $this->createCategoriesObject($command);

            $article->setCategories($categories);
        }

        return $this->articleRepository->update($article);
    }

    /**
     * @param FindArticlesByIdQuery $query
     * @param User $user
     * @return Article
     * @throws ArticleNotFoundException
     * @throws CompanyNotFoundException
     */
    public function fromArticle(FindArticlesByIdQuery $query, User $user)
    {
        $company = $this->companyRepository->fromId($query->getIdCompany());
        if (is_null($company)) {
            throw new CompanyNotFoundException();
        }

        $article = $this->articleRepository->fromId($query->getIdArticle());
        if (is_null($article)) {
            throw new ArticleNotFoundException();
        }

        return $article;
    }

    /**
     * @param DeleteArticleCommand $query
     * @param User $user
     * @throws ArticleNotFoundException
     * @throws CompanyNotFoundException
     */
    public function delete(DeleteArticleCommand $query, User $user)
    {
        $company = $this->companyRepository->fromId($query->getIdCompany());
        if (is_null($company)) {
            throw new CompanyNotFoundException();
        }

        $article = $this->articleRepository->fromId($query->getIdArticle());
        if (is_null($article)) {
            throw new ArticleNotFoundException();
        }

        $this->articleRepository->delete($article);
    }

}