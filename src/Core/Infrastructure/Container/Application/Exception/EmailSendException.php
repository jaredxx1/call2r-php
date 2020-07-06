<?php


namespace App\Core\Infrastructure\Container\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EmailSendException extends Exception
{
    public function __construct($message = "Error sending email", $code = Response::HTTP_INTERNAL_SERVER_ERROR, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}