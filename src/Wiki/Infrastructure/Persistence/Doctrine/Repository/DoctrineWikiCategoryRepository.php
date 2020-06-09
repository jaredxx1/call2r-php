<?php


namespace App\Wiki\Infrastructure\Persistence\Doctrine\Repository;


use App\Company\Application\Exception\DuplicatedCompanyException;
use App\Wiki\Application\Exception\DuplicatedWikiCategoryException;
use App\Wiki\Domain\Entity\WikiCategory;
use App\Wiki\Domain\Repository\WikiCategoryRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class DoctrineWikiCategoryRepository implements WikiCategoryRepository
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
        $this->repository = $entityManager->getRepository(WikiCategory::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @return object[]
     */
    public function getAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @param WikiCategory $wikiCategory
     * @return WikiCategory
     */
    public function create(WikiCategory $wikiCategory): ?WikiCategory
    {
        try{
            $this->entityManager->persist($wikiCategory);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new DuplicatedWikiCategoryException();
        }
        return $wikiCategory;
    }
}