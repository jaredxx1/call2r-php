<?php


namespace App\Company\Application\Exception;


use Exception;
use Throwable;

class CompanyNotFoundException extends Exception
{
    public function __construct($message = "Company not found", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}