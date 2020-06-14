<?php


namespace App\Core\Infrastructure\Container\Application\Service;


use App\Security\User;
use Exception;
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

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'error' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request)
    {
        return true;
    }

    public function getCredentials(Request $request)
    {
        $authorization = $request->headers->get('Authorization');
        return str_replace(['Bearer', ' '], '', $authorization);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $key = "example_key";

        try {
            $jwtPayload = JWT::decode($credentials, $key, ['HS256']);
        } catch (SignatureInvalidException $exception) {
            throw new AuthenticationException('Token is invalid', 401);
        }

        // 2. Fetch user
        // 3. Return user

        $user = new User();
        $user->setCpf('00000000000');
        $user->setRoles(['ROLE_USER']);

        return $user;
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