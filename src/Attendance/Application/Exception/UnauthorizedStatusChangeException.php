<?php


namespace App\Attendance\Application\Exception;

use Throwable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UnauthorizedStatusChangeException
 * @package App\Attendance\Application\Exception
 */
class UnauthorizedStatusChangeException extends \Exception
{
    public function __construct($message = "Unauthorized status change.", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}