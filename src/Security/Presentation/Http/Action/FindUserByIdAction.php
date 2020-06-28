<?php


namespace App\Security\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\Security\Application\Query\FindUserByIdQuery;
use App\Security\Application\Service\UserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class FindUserByIdAction
 * @package App\Security\Presentation\Http\Action
 */
class FindUserByIdAction extends AbstractAction
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
            $data = ['id' => $id];
            $query = FindUserByIdQuery::fromArray($data);
            $user = $this->userService->fromId($query->getId());
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }

        return new JsonResponse($user, 200);
    }
}