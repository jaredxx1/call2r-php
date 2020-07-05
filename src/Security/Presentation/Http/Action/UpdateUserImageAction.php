<?php


namespace App\Security\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\Security\Application\Command\UpdateUserCommand;
use App\Security\Application\Command\UpdateUserImageCommand;
use App\Security\Application\Service\UserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class UpdateUserImageAction
 * @package App\Security\Presentation\Http\Action
 */
class UpdateUserImageAction extends AbstractAction
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
     * @param int $idUser
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $idUser)
    {
        try {
            $user = $this->userService->fromId($idUser);
            $image = $request->files->getIterator()['image'];
            $user = $this->userService->updateImage($user, $image);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($user, Response::HTTP_OK);
    }
}