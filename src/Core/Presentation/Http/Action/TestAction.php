<?php


namespace App\Core\Presentation\Http\Action;


use App\Attendance\Application\Service\RequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TestAction
{

    public function __invoke()
    {
        return new JsonResponse([], Response::HTTP_OK);
    }

}