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
     * @param Request $request
     * @return Request|null
     */
    public function create(Request $request): ?Request
    {
        $this->entityManager->persist($request);
        $this->entityManager->flush();

        return $request;
    }

    /**
     * @param int $id
     * @return Request|null
     */
    public function fromId(int $id): ?Request
    {
        return $this->repository->find($id);
    }

    /**
     * @param Request $request
     * @return Request|null
     */
    public function update(Request $request): ?Request
    {
        $this->entityManager->flush();

        return $request;
    }
}