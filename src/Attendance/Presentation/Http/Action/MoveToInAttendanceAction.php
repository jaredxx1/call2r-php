<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Attendance\Application\Exception\RequestNotFoundException;
use App\Attendance\Application\Service\RequestService;
use App\Core\Presentation\Http\AbstractAction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class MoveToInAttendanceAction
 * @package App\Attendance\Presentation\Http\Action
 */
class MoveToInAttendanceAction extends AbstractAction
{
    /**
     * @var RequestService
     */
    private $service;

    /**
     * MoveToInAttendanceAction constructor.
     * @param RequestService $service
     */
    public function __construct(RequestService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @param int $requestId
     * @return JsonResponse
     * @throws RequestNotFoundException
     */
    public function __invoke(Request $request, int $requestId)
    {
        try {
            $request = $this->service->findById($requestId);
            $request = $this->service->moveToInAttendance($request);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }

        return new JsonResponse($request, Response::HTTP_ACCEPTED);
    }
}