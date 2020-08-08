<?php


namespace App\Core\Infrastructure\Container\Application\Service;


use App\User\Application\Service\UserService;
use App\User\Domain\Entity\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Class TokenAuthenticator
 * @package App\Core\Infrastructure\Container\Application\Service
 */
class TokenAuthenticator extends AbstractGuardAuthenticator
{

    /**
     * @var UserService
     */
    private $userService;

    /**
     * TokenAuthenticator constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return JsonResponse|Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'error' => 'Invalid authentication',
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }

    /**
     * @param Request $request
     * @return mixed|string|string[]|null
     */
    public function getCredentials(Request $request)
    {
        $authorization = $request->headers->get('Authorization');
        return str_replace(['Bearer', ' '], '', $authorization);
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return User|UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $jwtPayload = JWT::decode($credentials, $_ENV['JWT_SECRET'], ['HS256']);
        } catch (SignatureInvalidException $exception) {
            throw new AuthenticationException('Token is invalid', 401);
        } catch (ExpiredException $exception) {
            throw new AuthenticationException('Token is expired', 401);
        }

        return $this->userService->fromCpf($jwtPayload->cpf);
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        // TODO: Check credentials

        return true;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response|void|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return Response|void|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}