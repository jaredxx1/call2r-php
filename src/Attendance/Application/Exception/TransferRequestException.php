<?php


namespace App\Attendance\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TransferRequestException  extends Exception
{
    public function __construct($message = "You don't have permissions to transfer this request", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}