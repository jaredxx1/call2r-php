<?php


namespace App\Company\Infrastructure\Persistence\Doctrine\Repository;


use App\Company\Domain\Entity\SLA;
use App\Company\Domain\Repository\SlaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class DoctrineSlaRepository implements SlaRepository
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
     * DoctrineSlaRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(SLA::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return SLA
     */
    public function fromId(int $id): ?SLA
    {
        return $this->repository->find($id);
    }

    /**
     * @param SLA $sla
     * @return SLA|null
     */
    public function update(SLA $sla): ?SLA
    {

        $this->entityManager->flush();

        return $sla;
    }
}