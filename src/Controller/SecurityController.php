<?php

namespace Lle\OAuthClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{

    public function login()
    {
        return $this->get('oauth2.registry')
        ->getClient('2le_oauth')
        ->redirect();
    }

    public function loginCheck()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }


    public function logoutOAuth()
    {
        return $this->redirect(getenv('DOMAIN') . 'logout');
    }
}
