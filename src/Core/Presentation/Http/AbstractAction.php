<?php


namespace App\Core\Presentation\Http;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractAction
 * @package App\Core\Presentation\Http
 */
class AbstractAction
{

    /**
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponse(string $message, int $code = Response::HTTP_BAD_REQUEST)
    {
        return new JsonResponse([
            'error' => $message,
        ], $code);
    }
}