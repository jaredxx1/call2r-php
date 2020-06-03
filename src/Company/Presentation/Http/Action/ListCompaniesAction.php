<?php


namespace App\Company\Presentation\Http\Action;


use Symfony\Component\HttpFoundation\JsonResponse;

class ListCompaniesAction
{
    public function __invoke()
    {
        return new JsonResponse([], 200);
    }
}