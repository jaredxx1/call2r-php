<?php


namespace App\Security\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\Security\Application\Command\UpdateUserCommand;
use App\Security\Application\Service\UserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class UpdateUserAction
 * @package App\Security\Presentation\Http\Action
 */
class UpdateUserAction extends AbstractAction
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
     * @param int $id
     */
    public function __invoke(Request $request, int $id)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $data['id'] = $id;
            $command = UpdateUserCommand::fromArray($data);
            $user = $this->userService->updateUser($command);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }

        return new JsonResponse($user, 200);
    }
}