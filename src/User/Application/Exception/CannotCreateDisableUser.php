<?php


namespace App\User\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class CannotCreateDisableUser
 * @package App\User\Application\Exception
 */
class CannotCreateDisableUser  extends Exception
{
    /**
     * CannotCreateDisableUser constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Cannot create a disable user.", $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}