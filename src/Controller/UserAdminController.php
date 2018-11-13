<?php

namespace Lle\OAuthClientBundle\Controller;

use Lle\OAuthClientBundle\Form\UserType;
use Lle\OAuthClientBundle\Service\OAuthApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class UserAdminController extends Controller
{

    private $api;

    public function __construct(OAuthApi $api){
        $this->api = $api;
    }

    public function index($page=1)
    {

        $user = $this->getUser();
        $code = explode('/', $user->getCodeClient())[0];
        $users = $this->api->get(['page'=>$page, 'codeClient'=> $code."%2F", 'pagination' => false]);
        return $this->render("@OAuthClient/user_admin.html.twig", array(
            "users" => $users,
        ));
    }

    public function edit(Request $request, $id)
    {
        $this->addFlash('success', 'Utilisateur modifié');
        $this->api->put($id, $request->query->all());
        return $this->redirectToRoute('admin_user');
    }

    public function delete(Request $request, $id)
    {
        $this->addFlash('success', 'Utilisateur supprimé');
        $this->api->delete($id);
        return $this->redirectToRoute('admin_user');
    }


    public function new(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){
            $response = $this->api->post(array_merge($form->getData(), ['codeClient'=> $user->getCodeClient()]));
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
