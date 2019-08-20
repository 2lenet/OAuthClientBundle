<?php

namespace Lle\OAuthClientBundle\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{

    private $parametersBag;
    private $oauthRegistry;

    public function __construct(ParameterBagInterface $parametersBag, ClientRegistry $oauthRegistry){
        $this->parametersBag = $parametersBag;
        $this->oauthRegistry = $oauthRegistry;
    }

    public function login()
    {
        return $this->oauthRegistry->getClient('2le_oauth')->redirect();
    }

    public function loginCheck()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutOAuth() {
        $tokenStorage = $this->get('security.token_storage');
        $tokenStorage->setToken();
        return $this->redirect($this->parametersBag->get('lle.oauth_client.domain') . 'logout');
    }
}
