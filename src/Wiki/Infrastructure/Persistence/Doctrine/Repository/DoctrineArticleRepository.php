<?php


namespace App\Wiki\Infrastructure\Persistence\Doctrine\Repository;


use App\Company\Domain\Entity\Company;
use App\Wiki\Domain\Entity\Article;
use App\Wiki\Domain\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class DoctrineArticleRepository implements ArticleRepository
{
    /**
     * @var ObjectRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * DoctrineWikiCategoryRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->repository = $entityManager->getRepository(Article::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param Company $company
     * @return array|int|string
     */
    public function getAll(Company $company)
    {
        $query = $this->entityManager->createQuery("SELECT a FROM App\Wiki\Domain\Entity\Article a WHERE a.company = ?1");
        $query->setParameter(1, $company);

        return $query->getArrayResult();
    }
}