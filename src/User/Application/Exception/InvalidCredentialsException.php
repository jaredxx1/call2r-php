<?php


namespace App\User\Application\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class InvalidCredentialsException
 * @package App\User\Application\Exception
 */
class InvalidCredentialsException extends Exception
{
    /**
     * InvalidCredentialsException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Invalid credentials", $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}