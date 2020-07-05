<?php


namespace App\Attendance\Application\Exception;


use Exception;
use Throwable;

/**
 * Class RequestNotFoundException
 * @package App\Attendance\Application\Exception
 */
class RequestNotFoundException extends Exception
{
    /**
     * RequestNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Request not found.", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}