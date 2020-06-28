<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Attendance\Application\Service\RequestService;
use App\Core\Presentation\Http\AbstractAction;
use Symfony\Component\HttpFoundation\JsonResponse;

class FindAllRequestsAction extends AbstractAction
{
    /**
     * @var RequestService
     */
    private $service;

    /**
     * FindAllRequestsAction constructor.
     * @param RequestService $service
     */
    public function __construct(RequestService $service)
    {
        $this->service = $service;
    }

    public function __invoke()
    {
        return new JsonResponse($this->service->findAllRequests(),200);
    }
}