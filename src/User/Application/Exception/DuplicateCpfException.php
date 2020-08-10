<?php


namespace App\User\Application\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DuplicateCpfException  extends Exception
{

    /**
     * DuplicatedCompanyException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "The cpf already exists.", $code = Response::HTTP_UNPROCESSABLE_ENTITY, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}