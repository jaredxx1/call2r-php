<?php


namespace App\Attendance\Infrastructure\Persistence\Doctrine\Repository;


use App\Attendance\Domain\Entity\Request;
use App\Attendance\Domain\Repository\RequestRepository;
use App\User\Domain\Entity\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

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
     * @param bool|null $awaitingSupport
     * @param bool|null $inAttendance
     * @param bool|null $awaitingResponse
     * @param bool|null $canceled
     * @param bool|null $approved
     * @param bool|null $active
     * @param User $user
     * @return array
     */
    public function findRequestsClient(?bool $awaitingSupport, ?bool $inAttendance, ?bool $awaitingResponse, ?bool $canceled, ?bool $approved, ?bool $active, User $user): array
    {
        $query = $this->entityManager
            ->createQueryBuilder()
            ->select('r')
            ->from('Attendance:Request', 'r');

        $query = $this->listRequestsParameters($awaitingSupport, $query, $inAttendance, $awaitingResponse, $canceled, $approved, $active);

        $query
            ->andWhere('r.requestedBy = :userId')
            ->setParameter('userId', $user->getId());

        return $query->getQuery()->getResult();
    }

    /**
     * @param bool|null $awaitingSupport
     * @param QueryBuilder $query
     * @param bool|null $inAttendance
     * @param bool|null $awaitingResponse
     * @param bool|null $canceled
     * @param bool|null $approved
     * @param bool|null $active
     * @return QueryBuilder
     */
    public function listRequestsParameters(?bool $awaitingSupport, QueryBuilder $query, ?bool $inAttendance, ?bool $awaitingResponse, ?bool $canceled, ?bool $approved, ?bool $active): QueryBuilder
    {
        if (isset($awaitingSupport)) {
            $query->orWhere('r.status = 1');
        }

        if (isset($inAttendance)) {
            $query->orWhere('r.status = 4');
        }

        if (isset($awaitingResponse)) {
            $query->orWhere('r.status = 5');
        }

        if (isset($canceled)) {
            $query->orWhere('r.status = 3');
        }

        if (isset($approved)) {
            $query->orWhere('r.status = 2');
        }

        if (isset($active)) {
            $query->orWhere('r.status = 1')
                ->orWhere('r.status = 4')
                ->orWhere('r.status = 5');
        }

        return $query;
    }

    /**
     * @param bool|null $awaitingSupport
     * @param bool|null $inAttendance
     * @param bool|null $awaitingResponse
     * @param bool|null $canceled
     * @param bool|null $approved
     * @param bool|null $active
     * @param User $user
     * @return array
     */
    public function findRequestsSupport(?bool $awaitingSupport, ?bool $inAttendance, ?bool $awaitingResponse, ?bool $canceled, ?bool $approved, ?bool $active, User $user): array
    {
        $query = $this->entityManager
            ->createQueryBuilder()
            ->select('r')
            ->from('Attendance:Request', 'r');

        $query = $this->listRequestsParameters($awaitingSupport, $query, $inAttendance, $awaitingResponse, $canceled, $approved, $active);

        $query
            ->andWhere('r.companyId = :companyId')
            ->andWhere('r.assignedTo = :userId or r.assignedTo IS NULL')
            ->setParameter('companyId', $user->getCompanyId())
            ->setParameter('userId', $user->getId());

        return $query->getQuery()->getResult();
    }

    /**
     * @param bool|null $awaitingSupport
     * @param bool|null $inAttendance
     * @param bool|null $awaitingResponse
     * @param bool|null $canceled
     * @param bool|null $approved
     * @param bool|null $active
     * @return array
     */
    public function findRequestsManagerClient(?bool $awaitingSupport, ?bool $inAttendance, ?bool $awaitingResponse, ?bool $canceled, ?bool $approved, ?bool $active): array
    {
        $query = $this->entityManager
            ->createQueryBuilder()
            ->select('r')
            ->from('Attendance:Request', 'r');

        $query = $this->listRequestsParameters($awaitingSupport, $query, $inAttendance, $awaitingResponse, $canceled, $approved, $active);

        return $query->getQuery()->getResult();
    }

    /**
     * @param bool|null $awaitingSupport
     * @param bool|null $inAttendance
     * @param bool|null $awaitingResponse
     * @param bool|null $canceled
     * @param bool|null $approved
     * @param bool|null $active
     * @param User $user
     * @return array
     */
    public function findRequestsManagerSupport(?bool $awaitingSupport, ?bool $inAttendance, ?bool $awaitingResponse, ?bool $canceled, ?bool $approved, ?bool $active, User $user): array
    {
        $query = $this->entityManager
            ->createQueryBuilder()
            ->select('r')
            ->from('Attendance:Request', 'r');

        $query = $this->listRequestsParameters($awaitingSupport, $query, $inAttendance, $awaitingResponse, $canceled, $approved, $active);

        $query
            ->andWhere('r.companyId = :companyId')
            ->setParameter('companyId', $user->getCompanyId());

        return $query->getQuery()->getResult();
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
            $query->andWhere('r.title LIKE :title')
                ->setParameter(':title', '%' . $title . '%');
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

        if (isset($companyId)) {
            $query->andWhere('r.companyId = :companyId')
                ->setParameter('companyId', $companyId);
        }

        return $query->getQuery()->getResult();

    }
}