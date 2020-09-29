<?php


namespace App\Company\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CannotUpdateSlaToMotherCompany  extends Exception
{
    /**
     * CompanyNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Cannot update sla to mother company", $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}