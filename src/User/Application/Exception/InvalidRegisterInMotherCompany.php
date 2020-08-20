<?php


namespace App\User\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class InvalidRegisterInMotherCompany
 * @package App\User\Application\Exception
 */
class InvalidRegisterInMotherCompany extends Exception
{
    /**
     * InvalidRegisterInMotherCompany constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Role type user cannot register in mother company", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}