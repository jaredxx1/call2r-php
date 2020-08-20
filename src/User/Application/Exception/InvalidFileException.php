<?php


namespace App\User\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class InvalidFileException
 * @package App\User\Application\Exception
 */
class InvalidFileException extends Exception
{
    /**
     * InvalidFileException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Invalid file format", $code = Response::HTTP_UNSUPPORTED_MEDIA_TYPE, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}