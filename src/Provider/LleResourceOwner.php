<?php

namespace Lle\OAuthClientBundle\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;

class LleResourceOwner implements ResourceOwnerInterface
{
    /** @var array */
    private $data;

    /** @var AccessToken */
    private $token;

    /**
     * Creates a new resource owner.
     *
     * @param array $response
     */
    public function __construct(array $response, AccessToken $token)
    {
        $this->data = $response;
        $this->token = $token;
    }
    /**
     * Returns the identifier of the authorized resource owner.
     *
     * @return int
     */
    public function getId()
    {
        return (int) $this->get('id');
    }

    /**
     * Username of the user.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->get('username');
    }

    /**
     * Last name of the user
     *
     * @return string
     */
    public function getNom()
    {
        return $this->get('nom');
    }

    /**
     * First name of the user
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->get('prenom');
    }

    /**
     * Code client of the user
     *
     * @return string
     */
    public function getCodeClient()
    {
        return $this->get('codeClient');
    }

    /**
     * Email address of the user.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->get('email');
    }

    /**
     * @return AccessToken
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Roles the user has
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->get('roles');
    }

    /**
     * Whether the user has activated 2 factor authentication
     * @return bool
     */
     public function getA2fActivated()
     {
         return $this->get('a2f_activated');
     }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @param  string     $key
     * @param  mixed|null $default
     * @return mixed|null
     */
    protected function get($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
}
