<?php


namespace App\Company\Application\Exception;


use Exception;
use Throwable;

class NonUniqueMotherCompanyException extends Exception
{
    public function __construct($message = "There is one more registered parent company", $code = 409, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}