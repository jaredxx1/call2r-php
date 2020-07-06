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
    public function fromId(int $id): ?Status
    {
        return $this->repository->find($id);
    }

    /**
     * @param string $name
     * @return Status|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function fromName(string $name): ?Status
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('s')
            ->from('Attendance:Status', 's')
            ->where('s.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}