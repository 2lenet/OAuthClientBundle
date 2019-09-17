<?php
namespace Lle\OAuthClientBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Lle\OAuthClientBundle\Model\UserTokenInterface;
use Lle\OAuthClientBundle\OAuthEvent;
use Lle\OAuthClientBundle\Repository\UserRepository;
use Lle\OAuthClientBundle\Service\OAuthApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;

class JsonSecurityController extends AbstractController
{
    private $api;
    private $em;
    private $classUser;
    private $keyField;
    private $tokenName;
    private $eventDispatcher;
    private $serializer;

    public function __construct(
        OAuthApi $api,
        EntityManagerInterface $em,
        ParameterBagInterface $parameterBag,
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer
    )
    {
        $this->api = $api;
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = $serializer;
        $this->classUser = $parameterBag->get('lle.oauth_client.class_user');
        $this->keyField= $parameterBag->get('lle.oauth_client.key_field');
        $this->tokenName = $parameterBag->get('lle.oauth_client.token_name');
    }

    public function jsonLogin(Request $request)
    {
        $json = json_decode($request->getContent());

        $id = $this->api->checkUser($json->username, $json->password);
        if ($id) {
            $this->eventDispatcher->dispatch(OAuthEvent::onLoginJsonUser, new GenericEvent($id, (array)$json));
            $user = $this->em->getRepository($this->classUser)->findOneBy([$this->keyField=>$id]);
            if ($user and $user instanceof UserTokenInterface) {
                if (!$user->getToken()) {
                    $user->generateToken();
                    $this->em->persist($user);
                    $this->em->flush();
                }
                return new JsonResponse([$this->tokenName => $user->getToken()]);
            } else {
                throw new AccessDeniedException();
            }
        }
        throw new AccessDeniedException();
    }

    public function jsonLogout(Request $request){
        return new JsonResponse(['status'=>'ok']);
    }
    
    public function jsonUser(Request $request){
        $response = new JsonResponse();
        if(method_exists($this->getUser(), 'toArray')){
            $json = $this->getUser()->toArray();
        }else{
            $json = json_decode($this->serializer->serialize($this->getUser(),'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]));
        }
        return new JsonResponse(['user'=>$json]);
    }
}