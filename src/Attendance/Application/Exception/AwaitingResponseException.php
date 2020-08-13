<?php


namespace App\Attendance\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AwaitingResponseException extends Exception
{
    public function __construct($message = "You don't have permissions to move this request to awaiting response", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}