<?php


namespace App\Core\Presentation\Http;


use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractAction
{

    /**
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponse(string $message, int $code = 400)
    {
        return new JsonResponse([
            'error' => $message,
        ], $code);
    }
}