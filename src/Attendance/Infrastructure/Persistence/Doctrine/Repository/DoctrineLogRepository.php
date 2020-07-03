<?php


namespace App\Attendance\Infrastructure\Persistence\Doctrine\Repository;


use App\Attendance\Domain\Entity\Log;
use App\Attendance\Domain\Repository\LogRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DoctrineLogRepository
 * @package App\Attendance\Infrastructure\Persistence\Doctrine\Repository
 */
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

    /**
     * @param Log $log
     * @return Log
     */
    public function create(Log $log): Log
    {
        $this->entityManager->persist($log);
        $this->entityManager->flush();

        return $log;
    }
}