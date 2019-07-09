<?php

namespace Lle\OAuthClientBundle\Security\Guard;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;

use Lle\OAuthClientBundle\Security\User\UserProvider;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class OAuthGuardAuthenticator extends SocialAuthenticator
{
    use TargetPathTrait;

    private $router;
    private $clientRegistry;

    public function __construct(RouterInterface $router, ClientRegistry $clientRegistry)
    {
        $this->router = $router;
        $this->clientRegistry = $clientRegistry;
    }

    public function supports(Request $request)
    {
        // On ne continue que si la requête correspond au check_path
        return $request->attributes->get('_route') === 'login_check';
    }

    public function getCredentials(Request $request)
    {
        // Cette fonction n'est appelée que si supports() retourne true
        return $this->fetchAccessToken($this->getLleClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // Récupération du resource owner
        $resource_owner = $this->getLleClient()->fetchUserFromToken($credentials);

        // Création de l'utilisateur local
        return $userProvider->loadUserWithResourceOwner($resource_owner);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
	    }
	    return new RedirectResponse('/');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {

        $data = array(
            'message' => 'Authentication Required'
        );

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    private function getLleClient()
    {
        return $this->clientRegistry->getClient('2le_oauth');
    }
}
