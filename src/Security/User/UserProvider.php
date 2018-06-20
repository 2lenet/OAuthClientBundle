<?php

namespace Lle\OAuthClientBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Lle\OAuthClientBundle\Security\User\User;

class UserProvider implements UserProviderInterface
{
    /*
        __construct: clientregistry + faire user ici
    */
    public function loadUserByUsername($username)
    {
        return new User($username);
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
