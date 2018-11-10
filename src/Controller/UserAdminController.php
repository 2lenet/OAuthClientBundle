<?php

namespace Lle\OAuthClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Lle\OAuthClientBundle\Security\User\User;


class UserAdminController extends Controller
{
   
    /**
     * admin index.
     * @Route("/admin/user/{page}", name="admin_user", requirements={"page"="\d+"})
     */

    public function index($page=1)
    {
        $guzzle   = $this->get('eight_points_guzzle.client.connect');
        $user = $this->getUser();
        $code = explode('/', $user->getCodeClient())[0];

        $response = $guzzle->request('POST', "/api/login_check"
            , [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode(["username" => "tmpuser", "password"=> "tmppassword"])
            ]);
        $r = json_decode($response->getBody()->getContents());
        $response = $guzzle->request('GET', "/api/users?pagination=false&page=$page&codeClient=$code%2F",
            ['headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'authorization' => 'Bearer '.$r->token
            ]]);
        $users = json_decode($response->getBody()->getContents());
        return $this->render("@OAuthClient/user_admin.html.twig", array(
            "users" => $users,
        ));
    }

    /**
     * admin index.
     * @Route("/admin/user/new", name="admin_user_new")
     */

    public function new()
    {    

        $user = new User('');                
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Enregistrer'))
            ->getForm();
        
        return $this->render("@OAuthClient/user_new.html.twig", array(
            'form' => $form->createView(),
        ));
    }
}
