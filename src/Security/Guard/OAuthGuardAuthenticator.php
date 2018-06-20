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

use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;

use Lle\OAuthClientBundle\Security\User\UserProvider;

class OAuthGuardAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->router = $router;
    }

    public function supports(Request $request)
    {
        // On ne continue que si la requête correspond au check_path
        return $request->attributes->get('_route') === 'login_check';
    }

    public function getCredentials(Request $request)
    {
        // Cette fonction n'est appelée que si supports() retourne true
        return $this->fetchAccessToken($this->getClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // Récupération du resource owner
        $oauth_user = $this->getClient()->fetchUserFromToken($credentials);

        // Création de l'utilisateur local
        // On peut bien sûr personnaliser l'utilisateur et sa création selon les besoins
        $user = $userProvider->loadUserByUsername($oauth_user->getUsername());

        // On lui met les données nécessaires
        // Vous pouvez personnaliser en fonction de vos besoins, le minimum étant les rôles
        $user->setRoles($oauth_user->getRoles());
        $user->setNom($oauth_user->getNom());
        $user->setPrenom($oauth_user->getPrenom());
        $user->setCodeClient($oauth_user->getCodeClient());
        $user->setEmail($oauth_user->getEmail());

        return $user;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // Redirection après connexion
        return new RedirectResponse($this->router->generate('home'));
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

    private function getClient()
    {
        return $this->clientRegistry
            ->getClient('2le_oauth');
    }

}
