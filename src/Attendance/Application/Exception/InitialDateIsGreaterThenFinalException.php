<?php


namespace App\Attendance\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class InitialDateIsGreaterThenFinalException
 * @package App\Attendance\Application\Exception
 */
class InitialDateIsGreaterThenFinalException extends Exception
{
    /**
     * InitialDateIsGreaterThenFinalException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Initial date is greater then final date.", $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}