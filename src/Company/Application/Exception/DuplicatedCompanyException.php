<?php


namespace App\Company\Application\Exception;


use Exception;
use Throwable;

class DuplicatedCompanyException extends Exception
{

    /**
     * DuplicatedCompanyException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "The company already exists.", $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}