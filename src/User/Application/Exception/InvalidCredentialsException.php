<?php


namespace App\User\Application\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InvalidCredentialsException extends Exception
{
    public function __construct($message = "Invalid credentials", $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}