<?php


namespace App\Attendance\Domain\Repository;


use App\Attendance\Domain\Entity\Request;
use App\User\Domain\Entity\User;

/**
 * Interface RequestRepository
 * @package App\Attendance\Domain\Repository
 */
interface RequestRepository
{
    /**
     * @param Request $request
     * @return Request|null
     */
    public function create(Request $request): ?Request;

    /**
     * @param int $id
     * @return Request|null
     */
    public function fromId(int $id): ?Request;


    /**
     * @param Request $request
     * @return Request|null
     */
    public function update(Request $request): ?Request;

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
    public function findRequestsClient(?bool $awaitingSupport, ?bool $inAttendance, ?bool $awaitingResponse, ?bool $canceled, ?bool $approved, ?bool $active, User $user): array;

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
    public function findRequestsSupport(?bool $awaitingSupport, ?bool $inAttendance, ?bool $awaitingResponse, ?bool $canceled, ?bool $approved, ?bool $active, User $user): array;

    /**
     * @param bool|null $awaitingSupport
     * @param bool|null $inAttendance
     * @param bool|null $awaitingResponse
     * @param bool|null $canceled
     * @param bool|null $approved
     * @param bool|null $active
     * @return array
     */
    public function findRequestsManagerClient(?bool $awaitingSupport, ?bool $inAttendance, ?bool $awaitingResponse, ?bool $canceled, ?bool $approved, ?bool $active): array;

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
    public function findRequestsManagerSupport(?bool $awaitingSupport, ?bool $inAttendance, ?bool $awaitingResponse, ?bool $canceled, ?bool $approved, ?bool $active, User $user): array;

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
    public function searchRequests(?string $title, ?string $initialDate, ?string $finalDate, ?int $statusId, ?int $assignedTo, ?int $requestedBy, ?int $companyId): array;
}