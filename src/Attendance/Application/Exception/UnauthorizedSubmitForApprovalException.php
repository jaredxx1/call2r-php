<?php


namespace App\Attendance\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class UnauthorizedSubmitForApprovalException
 * @package App\Attendance\Application\Exception
 */
class UnauthorizedSubmitForApprovalException extends Exception
{
    /**
     * UnauthorizedSubmitForApprovalException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "You don't have permissions to submit this request to approve", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}