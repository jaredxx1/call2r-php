<?php


namespace App\Security\Application\Exception;


use Exception;
use Throwable;

/**
 * Class UserNotFoundException
 * @package App\Security\Application\Exception
 */
class UserNotFoundException extends Exception
{
    /**
     * UserNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "User not found", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}