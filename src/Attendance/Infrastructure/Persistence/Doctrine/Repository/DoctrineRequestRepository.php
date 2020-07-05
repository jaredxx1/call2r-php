<?php


namespace App\Attendance\Infrastructure\Persistence\Doctrine\Repository;


use App\Attendance\Domain\Entity\Request;
use App\Attendance\Domain\Repository\RequestRepository;
use App\Security\Domain\Entity\User;
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

    /**
     * @param User $user
     * @return array
     */
    public function findRequestsToClient(User $user): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('r')
            ->from('Attendance:Request', 'r')
            ->where('r.requestedBy = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @return array
     */
    public function findRequestsToManager(User $user): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('r')
            ->from('Attendance:Request', 'r')
            ->where('r.requestedBy = :userId')
            ->orWhere('r.assignedTo = :userId')
            ->orWhere('r.companyId = :companyId')
            ->setParameter('companyId', $user->getCompanyId())
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @return array
     */
    public function findRequestsToSupport(User $user): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('r')
            ->from('Attendance:Request', 'r')
            ->where('r.requestedBy = :userId')
            ->orWhere('r.assignedTo = :userId')
            ->orWhere('r.companyId = :companyId AND r.assignedTo IS NULL')
            ->setParameter('companyId', $user->getCompanyId())
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }
}