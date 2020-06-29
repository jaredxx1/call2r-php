<?php


namespace App\Attendance\Infrastructure\Persistence\Doctrine\Repository;


use App\Attendance\Domain\Repository\LogRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineLogRepository implements LogRepository
{
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
        $this->entityManager = $entityManager;
    }
}