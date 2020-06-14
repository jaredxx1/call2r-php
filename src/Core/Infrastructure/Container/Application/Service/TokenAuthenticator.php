<?php


namespace App\Core\Infrastructure\Container\Application\Service;


use App\Security\Application\Service\UserService;
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

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'error' => $authException->getMessage(),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }

    public function getCredentials(Request $request)
    {
        $authorization = $request->headers->get('Authorization');
        return str_replace(['Bearer', ' '], '', $authorization);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $key = "example_key";

        dd($credentials);

        try {
            $jwtPayload = JWT::decode($credentials, $key, ['HS256']);
        } catch (SignatureInvalidException $exception) {
            throw new AuthenticationException('Token is invalid', 401);
        }

        return $this->userService->fromCpf($jwtPayload->cpf);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // TODO: Check credentials

        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    public function supportsRememberMe()
    {
        return false;
    }
}