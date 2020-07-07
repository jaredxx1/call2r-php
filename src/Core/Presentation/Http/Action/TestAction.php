<?php


namespace App\Core\Presentation\Http\Action;


use App\Attendance\Application\Service\RequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TestAction
{

    /**
     * @var RequestService
     */
    private $service;

    /**
     * TestAction constructor.
     * @param RequestService $service
     */
    public function __construct(RequestService $service)
    {
        $this->service = $service;
    }

    public function __invoke()
    {
        $request = $this->service->findById(1);
        RequestService::calculateSla($request);

        return new JsonResponse($request, Response::HTTP_OK);
    }

}