<?php


namespace App\Wiki\Application\Exception;


use Exception;
use Throwable;

class CategoryNotAuthorized extends Exception
{
    /**
     * CompanyNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "This Category is not accessible for this user", $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}