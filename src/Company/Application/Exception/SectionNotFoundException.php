<?php


namespace App\Company\Application\Exception;


use Exception;
use Throwable;

/**
 * Class SectionNotFoundException
 * @package App\Company\Application\Exception
 */
class SectionNotFoundException extends Exception
{

    /**
     * SectionNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Section not found", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}