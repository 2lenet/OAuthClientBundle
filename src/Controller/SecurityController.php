<?php

namespace Lle\OAuthClientBundle\Controller;

use Lle\OAuthClientBundle\Service\OAuth2Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityController extends AbstractController
{
    public function __construct(
        private ParameterBagInterface $parametersBag,
        private TokenStorageInterface $tokenStorage,
        private OAuth2Service $auth2Service,
    ) {
    }

    public function login(Request $request)
    {
        return $this->auth2Service->authorize($request);
    }

    public function loginCheck()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutOAuth()
    {
        $this->tokenStorage->setToken();

        return $this->redirect($this->parametersBag->get('lle.oauth_client.domain') . 'logout');
    }
}
