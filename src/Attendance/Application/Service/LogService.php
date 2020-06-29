<?php


namespace App\Attendance\Application\Service;


use App\Attendance\Domain\Repository\LogRepository;

final class LogService
{

    /**
     * @var LogRepository
     */
    private $logRepository;

    /**
     * LogService constructor.
     * @param LogRepository $logRepository
     */
    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }
}