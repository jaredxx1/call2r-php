<?php

declare(strict_types=1);

namespace App\Blog\Presentation\Http\Action;

use App\Blog\Application\Service\BlogService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ListPostAction
{

    /**
     * @var BlogService
     */
    private $service;

    public function __construct(BlogService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request)
    {
        $id = $request->attributes->getInt('id');

        try {
            $post = $this->service->getPostById($id);
        } catch (Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], $exception->getCode());
        }

        return new JsonResponse($post, 200);
    }
}