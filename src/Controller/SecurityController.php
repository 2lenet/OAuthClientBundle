<?php

namespace Lle\OAuthClientBundle\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityController extends AbstractController
{
    private ParameterBagInterface $parametersBag;

    private ClientRegistry $oauthRegistry;

    private TokenStorageInterface $tokenStorage;

    public function __construct(
        ParameterBagInterface $parametersBag,
        ClientRegistry $oauthRegistry,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->parametersBag = $parametersBag;
        $this->oauthRegistry = $oauthRegistry;
        $this->tokenStorage = $tokenStorage;
    }

    public function login()
    {
        return $this->oauthRegistry->getClient('2le_oauth')->redirect();
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
