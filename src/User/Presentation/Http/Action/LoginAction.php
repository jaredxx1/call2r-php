<?php


namespace App\User\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\User\Application\Command\LoginCommand;
use App\User\Application\Service\UserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class LoginAction
 * @package App\User\Presentation\Http\Action
 */
class LoginAction extends AbstractAction
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
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {

        try {
            $data = json_decode($request->getContent(), true);
            $command = LoginCommand::fromArray($data);
            $user = $this->userService->fromLoginCredentials($command);
            $token = $this->userService->generateAuthToken($user);

            $response = [
                'token' => $token,
                'roles' => $user->getRoles(),
                'name' => $user->getName(),
                'companyId' => $user->getCompanyId(),
                'image_url' => $user->getImage()
            ];
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }

}