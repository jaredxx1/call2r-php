<?php


namespace App\User\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InvalidUserPrivileges extends Exception
{
    public function __construct($message = "Invalid user privileges", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}