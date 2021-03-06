<?php


namespace App\Attendance\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class UnauthorizedDisapproveRequestException
 * @package App\Attendance\Application\Exception
 */
class UnauthorizedDisapproveRequestException extends Exception
{
    /**
     * UnauthorizedDisapproveRequestException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "You don't have permissions to disapprove this request", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}