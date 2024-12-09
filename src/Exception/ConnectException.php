<?php

namespace Lle\OAuthClientBundle\Exception;

class ConnectException extends \Exception
{
    // requested resource does not exist
    public const NOT_FOUND = 1;
    
    protected array $data = [];

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
