<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Attendance\Application\Command\CreateRequestCommand;
use App\Attendance\Application\Service\RequestService;
use App\Core\Infrastructure\Container\Application\Service\TokenAuthenticator;
use App\Core\Presentation\Http\AbstractAction;
use App\Security\Application\Service\UserService;
use Exception;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @var TokenAuthenticator
     */
    private $tokenAuthenticator;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * CreateRequestAction constructor.
     * @param RequestService $requestService
     * @param TokenAuthenticator $tokenAuthenticator
     * @param UserService $userService
     */
    public function __construct(RequestService $requestService, TokenAuthenticator $tokenAuthenticator, UserService $userService)
    {
        $this->requestService = $requestService;
        $this->tokenAuthenticator = $tokenAuthenticator;
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
            $jwt = $this->tokenAuthenticator->getCredentials($request);
            $token = JWT::decode($jwt, $_ENV['JWT_SECRET'], ['HS256']);
            $data['token'] = (array) $token;
            $command = CreateRequestCommand::fromArray($data);
            $request = $this->requestService->create($command);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse($request, Response::HTTP_CREATED);
    }
}