<?php

namespace Lle\OAuthClientBundle\Exception;

class ConnectException extends \Exception
{
    // default
    public const int UNKNOWN_ERROR = 0;
    // requested resource does not exist
    public const int NOT_FOUND = 1;
    // username or email already taken
    public const int USER_ALREADY_EXISTS = 2;
    // validation (asserts) failed; see content
    public const int VALIDATION_FAILED = 3;

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
