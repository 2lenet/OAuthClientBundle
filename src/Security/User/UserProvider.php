<?php

namespace Lle\OAuthClientBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;

use Lle\OAuthClientBundle\Security\User\User;
use Lle\OAuthClientBundle\Provider\LleResourceOwner;

class UserProvider implements UserProviderInterface
{
    /**
     * To get OAuth clients
     * @var ClientRegistry
     */
    private $clientRegistry;

    public function __construct(ClientRegistry $clientRegistry)
    {
        $this->clientRegistry = $clientRegistry;
    }

    public function loadUserByUsername($username)
    {
        return new User($username);
    }

    /**
     * @return UserInterface
     */
    public function loadUserWithResourceOwner(LleResourceOwner $resource_owner)
    {
        $user = new User($resource_owner->getUsername());

        $user->setRoles($resource_owner->getRoles());
        $user->setId($resource_owner->getId());
        $user->setNom($resource_owner->getNom());
        $user->setPrenom($resource_owner->getPrenom());
        $user->setCodeClient($resource_owner->getCodeClient());
        $user->setOriginCodeClient($resource_owner->getCodeClient());
        $user->setEmail($resource_owner->getEmail());
        $user->setA2fActivated($resource_owner->getA2fActivated());

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        // As the eventual changes are literally made on another website we don't bother reloading it
        return $user;
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
