<?php


namespace App\Wiki\Infrastructure\Persistence\Doctrine\Repository;


use App\Wiki\Application\Exception\DuplicatedCategoryException;
use App\Wiki\Domain\Entity\Category;
use App\Wiki\Domain\Repository\CategoryRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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

    /**
     * @return Category[]|mixed|object[]
     */
    public function getAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @param Category $category
     * @return Category|null
     */
    public function update(Category $category): ?Category
    {
        $this->entityManager->flush();

        return $category;
    }

    /**
     * @param int $id
     * @return Category|null
     */
    public function fromId(int $id): ?Category
    {
        return $this->repository->find($id);
    }

    /**
     * @param string $title
     * @param int $idCompany
     * @return Category|null
     * @throws NonUniqueResultException
     */
    public function fromArticleTitle(string $title, int $idCompany): ?Category
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('c')
            ->from('Wiki:Category', 'c')
            ->where('c.title = :title and c.idCompany = :idCompany')
            ->setParameter('title', $title)
            ->setParameter('idCompany', $idCompany)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $id
     * @return array|int|string
     */
    public function fromCompany(int $id)
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('c')
            ->from('Wiki:Category', 'c')
            ->where('c.idCompany = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param Category $category
     * @return mixed
     */
    public function delete(Category $category): void
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }
}