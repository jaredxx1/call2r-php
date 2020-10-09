<?php


namespace App\Company\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class InvalidCnpjException
 * @package App\Company\Application\Exception
 */
class InvalidCnpjException extends Exception
{
    /**
     * InvalidCnpjException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Invalid CNPJ", $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}