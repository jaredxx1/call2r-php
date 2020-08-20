<?php


namespace App\Attendance\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class UnauthorizedMoveToCanceledException
 * @package App\Attendance\Application\Exception
 */
class UnauthorizedMoveToCanceledException extends Exception
{
    /**
     * UnauthorizedMoveToCanceledException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "You don't have permissions to cancel this request", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}