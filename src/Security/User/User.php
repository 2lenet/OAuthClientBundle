<?php

namespace Lle\OAuthClientBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;


class User implements UserInterface
{
    private $username;
    private $roles;
    private $nom;
    private $prenom;
    private $codeClient;
    private $email;
    private $a2fActivated;

    public function __construct($username)
    {
        $this->username = $username;
        $this->roles = array("ROLE_USER");
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }

    public function getPassword()
    {
        return '';
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        // Does nothing.
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getCodeClient()
    {
        return $this->codeClient;
    }

    /**
     * @param string $codeClient
     */
    public function setCodeClient($codeClient)
    {
        $this->codeClient = $codeClient;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isA2fActivated()
    {
        return $this->a2fActivated;
    }

    /**
     * @param bool $a2f_activated
     */
    public function setA2fActivated($a2fActivated)
    {
        $this->a2fActivated = $a2fActivated;
    }
}
