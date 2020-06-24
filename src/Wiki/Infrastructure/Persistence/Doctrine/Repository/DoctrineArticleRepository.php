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

    /**
     * @param Article $article
     * @return Article|null
     */
    public function create(Article $article): ?Article
    {
        $this->entityManager->persist($article);
        $this->entityManager->flush();
        return $article;
    }

    /**
     * @param int $id
     * @return Article|null
     */
    public function fromId(int $id): ?Article
    {
        return $this->repository->find($id);
    }

    /**
     * @param Article $article
     * @return Article|null
     */
    public function update(Article $article): ?Article
    {
        $this->entityManager->flush();
        return $article;
    }

    /**
     * @param Article $article
     * @return void
     */
    public function delete(Article $article)
    {
        $this->entityManager->remove($article);
        $this->entityManager->flush();
    }
}