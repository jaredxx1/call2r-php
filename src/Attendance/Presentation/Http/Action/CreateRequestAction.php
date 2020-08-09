<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Attendance\Application\Command\CreateRequestCommand;
use App\Attendance\Application\Service\RequestService;
use App\Core\Presentation\Http\AbstractAction;
use App\User\Application\Service\UserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

/**
 * Class CreateRequestAction
 * @package App\Attendance\Presentation\Http\Action
 */
class CreateRequestAction extends AbstractAction
{
    /**
     * @var RequestService
     */
    private $requestService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * CreateRequestAction constructor.
     * @param RequestService $requestService
     * @param UserService $userService
     */
    public function __construct(RequestService $requestService, UserService $userService)
    {
        $this->requestService = $requestService;
        $this->userService = $userService;
    }


    /**
     * @param Request $request
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function __invoke(Request $request, UserInterface $user)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $command = CreateRequestCommand::fromArray($data);
            $request = $this->requestService->create($command, $user);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse($request, Response::HTTP_CREATED);
    }
}