<?php


namespace App\User\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CreateUserException extends Exception
{
    public function __construct($message = "You dont have permission to create this user.", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}