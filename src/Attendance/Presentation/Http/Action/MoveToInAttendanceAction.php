<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Attendance\Application\Exception\RequestNotFoundException;
use App\Attendance\Application\Service\RequestService;
use App\Core\Presentation\Http\AbstractAction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
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
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $requestId, UserInterface $user)
    {
        try {
            $request = $this->service->findById($requestId);
            $request = $this->service->moveToInAttendance($request, $user);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($request, Response::HTTP_ACCEPTED);
    }
}