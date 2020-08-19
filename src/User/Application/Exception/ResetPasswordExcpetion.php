<?php


namespace App\User\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ResetPasswordExcpetion extends Exception
{
    public function __construct($message = "Cannot reset password because birthdate or cpf is invalid.", $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}