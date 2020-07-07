<?php


namespace App\Attendance\Infrastructure\Persistence\Doctrine\Repository;

use App\Attendance\Domain\Entity\Status;
use App\Attendance\Domain\Repository\StatusRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineStatusRepository implements StatusRepository
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
     * DoctrineCompanyRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->repository = $entityManager->getRepository(Status::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return Status|null
     */
    public function fromId(?int $id): ?Status
    {
        return $this->repository->find($id);
    }
}