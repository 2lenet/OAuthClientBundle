<?php

namespace Lle\OAuthClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
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

    public function url($url, $method = "GET", $data = null){
        $guzzle   = $this->get('eight_points_guzzle.client.connect');
        $response = $guzzle->request('POST', "/api/login_check"
            ,[
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode(["username" => "tmpuser","password"=> "tmppassword"])
            ]);
        $r = json_decode($response->getBody()->getContents());
        $options = ['headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'authorization' => 'Bearer '.$r->token
        ]];
        if($data){
            $options['body'] = json_encode($data);
        }
        $response = $guzzle->request($method, $url, $options);
        return json_decode($response->getBody()->getContents());
    }
   
    /**
     * admin index.
     * @Route("/admin/user/{page}", name="admin_user", requirements={"page"="\d+"})
     */

    public function index($page=1)
    {
        $user = $this->getUser();
        $code = explode('/', $user->getCodeClient())[0];
        $users = $this->url("/api/users?pagination=false&page=".$page."&codeClient=".$code."%2F");
        return $this->render("@OAuthClient/user_admin.html.twig", array(
            "users" => $users,
        ));
    }

    /**
     * admin index.
     * @Route("/admin/user/new", name="admin_user_new")
     */

    public function new(Request $request)
    {
        $form = $this->createFormBuilder([])
            ->add('username', TextType::class, ['attr' => ['class'=> 'form-control']])
            ->add('lastname', TextType::class, ['label'=> 'Nom', 'attr' => ['class'=> 'form-control']])
            ->add('firstname', TextType::class, ['label'=> 'Prenom', 'attr' => ['class'=> 'form-control']])
            ->add('email', TextType::class, ['attr' => ['class'=> 'form-control']])
            ->add('password', RepeatedType::class, [
                'type'=> PasswordType::class,
                'first_options' => ['label' => 'Mot de passe', 'attr' => ['class'=> 'form-control']],
                'second_options' => ['label' => 'Répéter mot de passe', 'attr' => ['class'=> 'form-control']]
            ])
            ->add('save', SubmitType::class, array('label' => 'Enregistrer', 'attr' => ['class'=> 'btn btn-primary']))
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $response = $this->url('/api/utils/users/add', 'POST', $form->getData());
            if($response->status === 'ok'){
                $this->addFlash('success', 'Utilisateur ajouté');
            }else{
                $this->addFlash('error', $response->error);
            }
        }
        return $this->render("@OAuthClient/user_new.html.twig", array(
            'form' => $form->createView(),
        ));
    }
}
