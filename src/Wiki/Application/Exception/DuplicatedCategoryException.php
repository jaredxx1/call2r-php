<?php


namespace App\Wiki\Application\Exception;


use Exception;
use Throwable;

/**
 * Class DuplicatedCategoryException
 * @package App\Wiki\Application\Exception
 */
class DuplicatedCategoryException extends Exception
{

    /**
     * DuplicatedCompanyException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "The category already exists.", $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}