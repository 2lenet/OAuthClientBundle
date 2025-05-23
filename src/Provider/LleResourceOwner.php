<?php

namespace Lle\OAuthClientBundle\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;

class LleResourceOwner implements ResourceOwnerInterface
{
    private array $data;

    /**
     * Creates a new resource owner.
     *
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->data = $response;
    }
    /**
     * Returns the identifier of the authorized resource owner.
     */
    public function getId(): int
    {
        return (int) $this->get('id');
    }

    /**
     * Username of the user.
     */
    public function getUsername(): string
    {
        return $this->get('username');
    }

    /**
     * Last name of the user
     */
    public function getNom(): string
    {
        return $this->get('nom');
    }

    /**
     * First name of the user
     */
    public function getPrenom(): string
    {
        return $this->get('prenom');
    }

    /**
     * Code client of the user
     */
    public function getCodeClient(): string
    {
        return $this->get('codeClient');
    }

    /**
     * Email address of the user.
     */
    public function getEmail(): string
    {
        return $this->get('email');
    }

    /**
     * Roles the user has
     */
    public function getRoles(): array
    {
        return $this->get('roles');
    }

    /**
     * Whether the user has activated 2 factor authentication
     */
     public function getA2fActivated(): bool
     {
         return $this->get('a2f_activated');
     }

     public function getMobile(): string
     {
         return $this->get('mobile');
     }

     public function getData(): array
     {
         return $this->get('data');
     }

     public function getProfiles(): array
     {
         return $this->get('profiles');
     }

     public function getTimezone(): string
     {
         return $this->get('timezone');
     }

     public function getLocale(): string
     {
         return $this->get('locale');
     }

    /**
     * Return all of the owner details available as an array.
     */
    public function toArray(): array
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
