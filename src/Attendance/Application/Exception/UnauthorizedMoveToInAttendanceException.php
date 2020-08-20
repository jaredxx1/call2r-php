<?php


namespace App\Attendance\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class UnauthorizedMoveToInAttendanceException
 * @package App\Attendance\Application\Exception
 */
class UnauthorizedMoveToInAttendanceException extends Exception
{
    /**
     * UnauthorizedMoveToInAttendanceException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "You don't have permissions to move this request to attendance", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}