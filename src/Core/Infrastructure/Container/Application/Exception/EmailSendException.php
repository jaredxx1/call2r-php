<?php


namespace App\Core\Infrastructure\Container\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class EmailSendException
 * @package App\Core\Infrastructure\Container\Application\Exception
 */
class EmailSendException extends Exception
{
    /**
     * EmailSendException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Error sending email", $code = Response::HTTP_INTERNAL_SERVER_ERROR, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}