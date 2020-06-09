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

    public function __construct($message = "Section not found", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}