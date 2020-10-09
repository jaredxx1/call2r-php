<?php


namespace App\Attendance\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class TransferRequestToYourOwnCompany
 * @package App\Attendance\Application\Exception
 */
class TransferRequestToYourOwnCompany extends Exception
{
    /**
     * TransferRequestToYourOwnCompany constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "You are trying to transfer the request to your own company.", $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}