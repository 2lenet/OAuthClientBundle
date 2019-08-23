<?php

namespace Lle\OAuthClientBundle\Security\Guard;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $classUser;
    private $headerTokenName;
    private $tokenNameField;
    private $tokenType;


    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameter)
    {
        $this->em = $em;
        $this->classUser = $parameter->get('lle.oauth_client.class_user');
        $this->headerTokenName = $parameter->get('lle.oauth_client.header_token_name');
        $this->tokenType = $parameter->get('lle.oauth_client.token_type');
        $this->tokenNameField = $parameter->get('lle.oauth_client.token_name_field');
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request)
    {
        $isToken = $request->headers->has($this->headerTokenName) && $request->headers->get($this->headerTokenName);
        return (
            $isToken &&
            ($this->tokenType === null or
                strstr( $request->headers->get($this->headerTokenName), $this->tokenType.' ')
            )
        );
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request)
    {
        return [
            'token' => str_replace($this->tokenType.' ','',$request->headers->get($this->headerTokenName))
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiToken = $credentials['token'];

        if (null === $apiToken) {
            return;
        }

        return $this->em->getRepository($this->classUser)->findOneBy([$this->tokenNameField => $apiToken]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
