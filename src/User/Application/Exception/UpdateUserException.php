<?php


namespace App\User\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class UpdateUserException
 * @package App\User\Application\Exception
 */
class UpdateUserException extends Exception
{
    /**
     * UpdateUserException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "You dont have permission to update this user.", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}