<?php


namespace App\Attendance\Infrastructure\Persistence\Doctrine\Repository;


use App\Attendance\Domain\Entity\Request;
use App\Attendance\Domain\Repository\RequestRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DoctrineRequestRepository
 * @package App\Attendance\Infrastructure\Persistence\Doctrine\Repository
 */
class DoctrineRequestRepository implements RequestRepository
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
        $this->repository = $entityManager->getRepository(Request::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->repository->findAll();
    }
}