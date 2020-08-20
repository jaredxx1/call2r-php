<?php


namespace App\User\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\User\Application\Command\UpdateUserImageCommand;
use App\User\Application\Service\UserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

/**
 * Class UpdateUserImageAction
 * @package App\User\Presentation\Http\Action
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
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $idUser, UserInterface $user)
    {
        try {
            $image = $request->files->getIterator()['image'];
            $data['id'] = $idUser;
            $data['uploadFile'] = $image;
            $command = UpdateUserImageCommand::fromArray($data);
            $user = $this->userService->updateImage($user, $command);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($user, Response::HTTP_OK);
    }
}