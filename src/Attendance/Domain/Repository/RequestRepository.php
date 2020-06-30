<?php


namespace App\Attendance\Domain\Repository;


use App\Attendance\Domain\Entity\Request;

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

    /**
     * @param Request $request
     * @return Request|null
     */
    public function create(Request $request): ?Request;
}