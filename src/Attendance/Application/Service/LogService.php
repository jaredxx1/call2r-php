<?php


namespace App\Attendance\Application\Service;


use App\Attendance\Application\Command\CreateLogCommand;
use App\Attendance\Domain\Entity\Log;
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

    /**
     * @param CreateLogCommand $command
     */
    public function create(CreateLogCommand $command) {
        dd($command);
    }

    /**
     * @param string $message
     * @param string $command
     */
    public static function registerEvent(string $message, string $command) {
        $log = new Log(null, $message, null, $command);

        dd($log);
    }
}