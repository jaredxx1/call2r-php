<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Attendance\Application\Service\RequestService;
use App\Core\Presentation\Http\AbstractAction;
use App\Security\Application\Service\UserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class FindRequestsAction
 * @package App\Attendance\Presentation\Http\Action
 */
class FindRequestsAction extends AbstractAction
{
    /**
     * @var RequestService
     */
    private $service;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * FindRequestsAction constructor.
     * @param RequestService $service
     * @param UserService $userService
     */
    public function __construct(RequestService $service, UserService $userService)
    {
        $this->service = $service;
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        try {
            $user = $this->userService->fromId(1);
            $requests = $this->service->findAll($user);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($requests, Response::HTTP_OK);
    }
}