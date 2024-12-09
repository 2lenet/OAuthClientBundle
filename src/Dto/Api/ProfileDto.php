<?php

namespace Lle\OAuthClientBundle\Dto\Api;

class ProfileDto
{
    protected ?string $name = null;

    public static function new(): self
    {
        return new self();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
