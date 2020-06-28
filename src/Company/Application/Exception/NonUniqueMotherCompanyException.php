<?php


namespace App\Company\Application\Exception;


use Exception;
use Throwable;

/**
 * Class NonUniqueMotherCompanyException
 * @package App\Company\Application\Exception
 */
class NonUniqueMotherCompanyException extends Exception
{
    /**
     * NonUniqueMotherCompanyException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "There is one more registered parent company", $code = 409, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}