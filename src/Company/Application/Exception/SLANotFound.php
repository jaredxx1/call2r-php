<?php


namespace App\Company\Application\Exception;


use Exception;
use Throwable;

class SLANotFound extends Exception
{
    public function __construct($message = "SLA not found", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}