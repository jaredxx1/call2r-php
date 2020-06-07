<?php


namespace App\Company\Infrastructure\Persistence\Doctrine\Repository;


use App\Company\Domain\Entity\SLA;
use App\Company\Domain\Repository\SLARepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class DoctrineSLARepository implements SLARepository
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
     * DoctrineSLARepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(SLA::class);
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
     * @param int $id
     * @return SLA|null
     */
    public function fromId(int $id): ?SLA
    {
        return $this->repository->find($id);
    }

    public function update(SLA $sla): ?SLA
    {
        $this->entityManager->flush();

        return $sla;
    }
}