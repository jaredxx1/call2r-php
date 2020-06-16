<?php


namespace App\Wiki\Infrastructure\Persistence\Doctrine\Repository;


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
     * @param int $id
     * @return array|int|string
     */
    public function fromCompany(int $id)
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('a')
            ->from('Wiki:Article', 'a')
            ->where('a.idCompany = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();
    }
}