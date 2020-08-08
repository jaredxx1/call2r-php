<?php


namespace App\User\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\User\Application\Query\FindUsersByRoleQuery;
use App\User\Application\Service\UserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class FindUsersByRoleAction
 * @package App\User\Presentation\Http\Action
 */
class FindUsersByRoleAction extends AbstractAction
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
     * @param string $role
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $role)
    {
        try {
            $data = ['role' => $role];
            $query = FindUsersByRoleQuery::fromArray($data);
            $users = $this->userService->findUsersByRole($query);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($users, Response::HTTP_OK);
    }
}