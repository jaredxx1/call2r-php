<?php


namespace App\Wiki\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class DuplicatedCategoryException
 * @package App\Wiki\Application\Exception
 */
class DuplicatedCategoryException extends Exception
{

    /**
     * DuplicatedCategoryException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "The category already exists.", $code = Response::HTTP_UNPROCESSABLE_ENTITY, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}