<?php


namespace App\User\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\User\Application\Command\CreateUserCommand;
use App\User\Application\Service\UserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

class CreateUserAction extends AbstractAction
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * LoginAction constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
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
            $command = CreateUserCommand::fromArray($data);
            $user = $this->userService->createUser($command, $user);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($user, Response::HTTP_CREATED);
    }
}