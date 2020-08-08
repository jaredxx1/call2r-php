<?php


namespace App\User\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InvalidFileException  extends Exception
{
    public function __construct($message = "Invalid file format", $code = Response::HTTP_UNSUPPORTED_MEDIA_TYPE, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}