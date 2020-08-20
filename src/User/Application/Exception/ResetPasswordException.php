<?php


namespace App\User\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ResetPasswordExcpetion
 * @package App\User\Application\Exception
 */
class ResetPasswordException extends Exception
{
    /**
     * ResetPasswordException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Cannot reset password because birthdate or cpf is invalid.", $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}