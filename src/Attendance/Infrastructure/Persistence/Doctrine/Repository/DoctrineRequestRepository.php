<?php


namespace App\Attendance\Infrastructure\Persistence\Doctrine\Repository;


use App\Attendance\Domain\Entity\Request;
use App\Attendance\Domain\Repository\RequestRepository;
use App\User\Domain\Entity\User;
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
            ->where('r.assignedTo = :userId')
            ->orWhere('r.companyId = :companyId AND r.assignedTo IS NULL')
            ->setParameter('companyId', $user->getCompanyId())
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }


    /**
     * @param string|null $title
     * @param string|null $initialDate
     * @param string|null $finalDate
     * @param int|null $statusId
     * @param int|null $assignedTo
     * @param int|null $requestedBy
     * @param int|null $companyId
     * @return array
     */
    public function searchRequests(?string $title, ?string $initialDate, ?string $finalDate, ?int $statusId, ?int $assignedTo, ?int $requestedBy, ?int $companyId): array
    {

        $query = $this->entityManager->createQueryBuilder()
            ->select('r')
            ->from('Attendance:Request', 'r');

        if (isset($title)) {
            $query->andWhere('r.title = :title')
                ->setParameter(':title', $title);
        }

        if ((isset($finalDate)) && (isset($initialDate))) {
            $query->andWhere('r.createdAt BETWEEN :initialDate and :finalDate')
                ->setParameter(':initialDate', $initialDate)
                ->setParameter(':finalDate', $finalDate);
        }

        if (isset($statusId)) {
            $query->andWhere('r.status = :status')
                ->setParameter(':status', $statusId);
        }

        if (isset($assignedTo)) {
            $query->andWhere('r.assignedTo = :assignedTo')
                ->setParameter(':assignedTo', $assignedTo);
        }

        if (isset($requestedBy)) {
            $query->andWhere('r.requestedBy = :requestedBy')
                ->setParameter(':requestedBy', $requestedBy);
        }

        if(isset($companyId)){
            $query->andWhere('r.companyId = :companyId')
                ->setParameter('companyId', $companyId);
        }

        return $query->getQuery()->getResult();

    }
}