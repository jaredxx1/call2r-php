<?php


namespace App\Attendance\Domain\Repository;


use App\Attendance\Domain\Entity\Request;
use App\Security\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @param User $user
     * @return array
     */
    public function findRequestsToClient(User $user): array;

    /**
     * @param User $user
     * @return array
     */
    public function findRequestsToManager(User $user): array;

    /**
     * @param User $user
     * @return array
     */
    public function findRequestsToSupport(User $user): array;
}