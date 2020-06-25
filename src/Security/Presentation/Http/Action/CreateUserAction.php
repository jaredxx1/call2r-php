<?php


namespace App\Security\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\Security\Application\Command\CreateUserCommand;
use App\Security\Application\Service\UserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    public function __invoke(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $command = CreateUserCommand::fromArray($data);
        $user = $this->userService->createUser($command);

        try {

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }

        return new JsonResponse($user, Response::HTTP_CREATED);
    }
}