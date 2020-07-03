<?php


namespace App\Attendance\Domain\Repository;


use App\Attendance\Domain\Entity\Log;

/**
 * Interface LogRepository
 * @package App\Attendance\Domain\Repository
 */
interface LogRepository
{
    /**
     * @param Log $log
     * @return Log
     */
    public function create(Log $log): Log;
}