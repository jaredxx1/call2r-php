<?php


namespace App\Wiki\Infrastructure\Persistence\Doctrine\Repository;


use App\Wiki\Domain\Entity\Category;
use App\Wiki\Domain\Repository\CategoryRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use \Doctrine\ORM\NonUniqueResultException;

/**
 * Class DoctrineCategoryRepository
 * @package App\Wiki\Infrastructure\Persistence\Doctrine\Repository
 */
class DoctrineCategoryRepository implements CategoryRepository
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
        $this->repository = $entityManager->getRepository(Category::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $title
     * @return Category|null
     * @throws NonUniqueResultException
     */
    public function fromTitle(string $title): ?Category
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('c')
            ->from('Wiki:Category', 'c')
            ->where('c.title = :title')
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult();
    }
}