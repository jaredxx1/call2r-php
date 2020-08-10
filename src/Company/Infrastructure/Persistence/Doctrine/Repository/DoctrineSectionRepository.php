<?php


namespace App\Company\Infrastructure\Persistence\Doctrine\Repository;


use App\Company\Domain\Entity\Section;
use App\Company\Domain\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ObjectRepository;

/**
 * Class DoctrineSectionRepository
 * @package App\Company\Infrastructure\Persistence\Doctrine\Repository
 */
class DoctrineSectionRepository implements SectionRepository
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
     * DoctrineSectionRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->repository = $entityManager->getRepository(Section::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @return object[]
     */
    public function getAll()
    {
        return $this->entityManager->getRepository(Section::class)->findAll();
    }

    /**
     * @param string $name
     * @return Section|null
     * @throws NonUniqueResultException
     */
    public function fromName(string $name): ?Section
    {
        $query = $this->entityManager->createQuery("SELECT s FROM App\Company\Domain\Entity\Section s WHERE s.name = ?1");
        $query->setParameter(1, $name);

        return $query->getOneOrNullResult();
    }

    /**
     * @param Section $section
     * @return Section|null
     */
    public function update(Section $section): ?Section
    {
        $this->entityManager->flush();

        return $section;
    }

    /**
     * @param int $id
     * @return Section|null
     */
    public function fromId(int $id): ?Section
    {
        return $this->repository->find($id);
    }
}