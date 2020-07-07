<?php


namespace App\Attendance\Domain\Repository;


use App\Attendance\Domain\Entity\Status;

/**
 * Interface StatusRepository
 * @package App\Attendance\Domain\Repository
 */
interface StatusRepository
{
    /**
     * @param int $id
     * @return Status|null
     */
    public function fromId(?int $id): ?Status;
}