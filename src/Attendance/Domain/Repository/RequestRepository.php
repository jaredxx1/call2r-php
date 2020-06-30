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
     * @param Request $request
     * @return Request|null
     */
    public function create(Request $request): ?Request;
}