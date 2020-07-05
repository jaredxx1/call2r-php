<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Attendance\Application\Service\RequestService;
use App\Core\Presentation\Http\AbstractAction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class MoveToCanceledAction
 * @package App\Attendance\Presentation\Http\Action
 */
class MoveToCanceledAction extends AbstractAction
{

    /**
     * @var RequestService
     */
    private $service;

    /**
     * MoveToCanceledAction constructor.
     * @param RequestService $service
     */
    public function __construct(RequestService $service)
    {
        $this->service = $service;
    }

    /**
     * @param int $requestId
     * @return JsonResponse
     */
    public function __invoke(int $requestId)
    {
        try {
            $request = $this->service->findById($requestId);
            $request = $this->service->moveToCanceled($request);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }

        return new JsonResponse($request, Response::HTTP_ACCEPTED);
    }
}