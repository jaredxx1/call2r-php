<?php


namespace App\Core\Infrastructure\Container\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InvalidDateFormatException  extends Exception
{
    /**
     * UnauthorizedStatusChangeException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Invalid date format", $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}