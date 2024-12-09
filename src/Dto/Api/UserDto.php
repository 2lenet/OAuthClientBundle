<?php

namespace Lle\OAuthClientBundle\Dto\Api;

/**
 * Properties do not have default values.
 * This is on purpose, it allows us to not have to send all properties to the API
 * (in case you need to edit only a few)
 */
class UserDto
{
    protected ?int $connectId;

    protected ?string $username;

    protected ?string $plainPassword;

    protected ?string $lastName;

    protected ?string $firstName;

    protected ?string $email;

    protected ?string $mobilePhone;

    protected ?bool $a2fActivated;

    protected ?string $a2fProvider;

    protected ?array $roles;

    protected ?bool $isActive;

    protected ?string $codeClient;

    protected ?string $data;

    protected ?string $autoRedirectUrl;

    protected ?string $locale;

    /** @var ProfileDto[]|null */
    protected ?array $profiles;

    public static function new(): self
    {
        return new self();
    }

    public function has(string $propertyName): bool
    {
        return isset($this->{$propertyName}) || $this->{$propertyName} === null;
    }

    public function getConnectId(): ?int
    {
        return $this->connectId;
    }

    public function setConnectId(?int $connectId): static
    {
        $this->connectId = $connectId;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    public function setMobilePhone(?string $mobilePhone): static
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    public function getA2fActivated(): ?bool
    {
        return $this->a2fActivated;
    }

    public function setA2fActivated(?bool $a2fActivated): static
    {
        $this->a2fActivated = $a2fActivated;

        return $this;
    }

    public function getA2fProvider(): ?string
    {
        return $this->a2fProvider;
    }

    public function setA2fProvider(?string $a2fProvider): static
    {
        $this->a2fProvider = $a2fProvider;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCodeClient(): ?string
    {
        return $this->codeClient;
    }

    public function setCodeClient(?string $codeClient): static
    {
        $this->codeClient = $codeClient;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getAutoRedirectUrl(): ?string
    {
        return $this->autoRedirectUrl;
    }

    public function setAutoRedirectUrl(?string $autoRedirectUrl): static
    {
        $this->autoRedirectUrl = $autoRedirectUrl;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function getProfiles(): ?array
    {
        return $this->profiles;
    }

    public function setProfiles(?array $profiles): static
    {
        $this->profiles = $profiles;

        return $this;
    }
}
