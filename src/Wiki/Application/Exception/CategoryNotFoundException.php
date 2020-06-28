<?php


namespace App\Wiki\Application\Exception;


use Exception;
use Throwable;

/**
 * Class CategoryNotFoundException
 * @package App\Wiki\Application\Exception
 */
class CategoryNotFoundException extends Exception
{
    /**
     * CompanyNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Category not found", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}