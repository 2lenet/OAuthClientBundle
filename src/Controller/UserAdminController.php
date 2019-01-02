<?php

namespace Lle\OAuthClientBundle\Controller;

use Lle\OAuthClientBundle\Form\EditUserType;
use Lle\OAuthClientBundle\Form\UserType;
use Lle\OAuthClientBundle\Service\OAuthApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Translation\TranslatorInterface;


class UserAdminController extends Controller
{

    private $api;
    private $trans;

    public function __construct(OAuthApi $api, TranslatorInterface $trans){
        $this->api = $api;
        $this->trans = $trans;
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
        /* @TODO test */
        $user = $this->api->find($id);
        $user->password = null;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $response = $this->api->update($id, (array)$form->getData());
            if($response->status === 'ok'){
                $this->addFlash('success', $this->trans->trans('flash.edit_success', [], 'LleOAuth'));
            }else{
                $this->addFlash('error', $response->error);
            }
        }
        return $this->render("@OAuthClient/user_edit.html.twig", array(
            'form' => $form->createView(),
        ));
    }

    public function put(Request $request, $id)
    {
        $this->addFlash('success', $this->trans->trans('flash.edit_success', [], 'LleOAuth'));
        $this->api->put($id, $request->query->all());
        return $this->redirectToRoute('admin_user');
    }

    public function delete(Request $request, $id)
    {
        $this->addFlash('success', $this->trans->trans('flash.delete_success', [], 'LleOAuth'));
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
                $this->addFlash('success', $this->trans->trans('flash.add_success', [], 'LleOAuth'));
            }else{
                $this->addFlash('error', $response->error);
            }
        }
        return $this->render("@OAuthClient/user_new.html.twig", array(
            'form' => $form->createView(),
        ));
    }


    public function formEdit(Request $request)
    {

        /* @var \App\Security\Authentication\Entity\User $user */
        $user = $this->getUser();
        $data = [
            'firstname' => $user->getPrenom(),
            'lastname' => $user->getNom(),
            'email' => $user->getEmail(),
            'mobile'=> $user->getMobile(),
        ];


        $form = $this->createForm(EditUserType::class, $data);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){
            $this->api->put($user->getId(),$form->getData());
            if($form->getData()['password']) {
                $this->api->putPassword($user->getId(), $form->getData()['password']);
            }
            $user->syncWith($form->getData());
            $this->addFlash('success', $this->trans->trans('flash.edit_success', [], 'LleOAuth'));
        }
        return $this->render("@OAuthClient/user_edit.html.twig", array(
            'form' => $form->createView(),
        ));
    }
}
