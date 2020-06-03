<?php


namespace App\Core\Presentation\Http\Action;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AliveAction
{
    public function __invoke(Request $request)
    {
        return new JsonResponse(['status' => 'ok'], 200);
    }
}