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
        $client   = $this->get('eight_points_guzzle.client.connect');
        $url = '/api/users';
        $user = $this->getUser();
        $code = explode('/', $user->getCodeClient())[0];
        $url .= "?pagination=false&page=$page&codeClient=$code%2F";
        $res = $client->get($url);
        $users = json_decode($res->getBody(), true);
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
