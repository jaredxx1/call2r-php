<?php


namespace App\Attendance\Domain\Repository;


/**
 * Interface RequestRepository
 * @package App\Attendance\Domain\Repository
 */
interface RequestRepository
{
    /**
     * @return array
     */
    public function getAll(): array;
}