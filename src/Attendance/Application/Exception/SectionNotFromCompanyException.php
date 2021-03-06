<?php


namespace App\Attendance\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class SectionNotFromCompanyException
 * @package App\Attendance\Application\Exception
 */
class SectionNotFromCompanyException extends Exception
{
    /**
     * SectionNotFromCompanyException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "This sections is not from this company", $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}