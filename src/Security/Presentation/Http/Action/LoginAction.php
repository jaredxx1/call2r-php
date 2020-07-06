<?php


namespace App\Security\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\Security\Application\Command\LoginCommand;
use App\Security\Application\Service\UserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class LoginAction
 * @package App\Security\Presentation\Http\Action
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
                'roles' => $user->getRoles()
            ];
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }

        return new JsonResponse($response, 200);
    }

}