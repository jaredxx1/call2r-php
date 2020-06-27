<?php


namespace App\Wiki\Application\Exception;


use Exception;
use Throwable;

class ArticleNotAvalible extends Exception
{
    /**
     * CompanyNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Article not available for this company", $code = 409, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}