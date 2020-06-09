<?php


namespace App\Wiki\Application\Exception;


use Symfony\Component\Config\Definition\Exception\Exception;
use Throwable;

class DuplicatedWikiCategoryException extends Exception
{
    /**
     * DuplicatedCompanyException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "The title for this Wiki category already exists.", $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}