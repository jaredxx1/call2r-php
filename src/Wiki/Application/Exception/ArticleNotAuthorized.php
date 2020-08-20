<?php


namespace App\Wiki\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ArticleNotAuthorized
 * @package App\Wiki\Application\Exception
 */
class ArticleNotAuthorized extends Exception
{
    /**
     * ArticleNotAuthorized constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "This Article is not accessible for this user", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}