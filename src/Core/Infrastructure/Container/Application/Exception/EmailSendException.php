<?php


namespace App\Core\Infrastructure\Container\Application\Exception;


use Exception;
use Throwable;

class EmailSendException extends Exception
{
    public function __construct($message = "Error sending email", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}