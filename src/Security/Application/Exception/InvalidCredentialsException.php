<?php


namespace App\Security\Application\Exception;

use Exception;
use Throwable;

class InvalidCredentialsException extends Exception
{
    public function __construct($message = "Invalid credentials", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}