<?php


namespace App\User\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class FromIdException
 * @package App\User\Application\Exception
 */
class FromIdException  extends Exception
{
    public function __construct($message = "You dont have permission to see this user.", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}