<?php


namespace App\Wiki\Application\Exception;


use Exception;
use Throwable;

class CategoryNotAvalible extends Exception
{
    /**
     * CompanyNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Category not available for this company", $code = 409, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}