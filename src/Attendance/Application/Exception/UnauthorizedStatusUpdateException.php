<?php


namespace App\Attendance\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class UnauthorizedStatusUpdateException
 * @package App\Attendance\Application\Exception
 */
class UnauthorizedStatusUpdateException extends Exception
{
    /**
     * UnauthorizedStatusUpdateException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Unauthorized status update.", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}