<?php

namespace Lle\OAuthClientBundle\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class LleResourceOwner implements ResourceOwnerInterface
{
    private array $data;

    public function __construct(array $response)
    {
        $this->data = $response;
    }

    public function getId(): int
    {
        return (int)$this->get('id');
    }

    public function getUsername(): string
    {
        return $this->get('username');
    }

    public function getNom(): ?string
    {
        return $this->get('nom');
    }

    public function getPrenom(): ?string
    {
        return $this->get('prenom');
    }

    public function getCodeClient(): ?string
    {
        return $this->get('codeClient');
    }

    public function getEmail(): string
    {
        return $this->get('email');
    }

    public function getRoles(): array
    {
        return $this->get('roles');
    }

    public function getA2fActivated(): ?bool
    {
        return $this->get('a2f_activated');
    }

    public function getMobile(): ?string
    {
        return $this->get('mobile');
    }

    public function getData(): ?array
    {
        return $this->get('data');
    }

    public function getProfiles(): array
    {
        return $this->get('profiles');
    }

    public function getTimezone(): ?string
    {
        return $this->get('timezone');
    }

    public function getLocale(): ?string
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

    protected function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }
}
