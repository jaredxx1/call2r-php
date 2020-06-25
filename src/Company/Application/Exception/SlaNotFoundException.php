<?php


namespace App\Company\Application\Exception;


use Exception;
use Throwable;

/**
 * Class SlaNotFoundException
 * @package App\Company\Application\Exception
 */
class SlaNotFoundException extends Exception
{
    /**
     * CompanyNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Sla not found", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}