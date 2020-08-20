<?php


namespace App\Company\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
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
    public function __construct($message = "There is one more registered parent company", $code = Response::HTTP_CONFLICT, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}