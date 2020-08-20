<?php


namespace App\Attendance\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class UnauthorizedRequestUpdateException
 * @package App\Attendance\Application\Exception
 */
class UnauthorizedRequestUpdateException extends Exception
{
    /**
     * UnauthorizedRequestUpdateException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Unauthorized request updated action.", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}