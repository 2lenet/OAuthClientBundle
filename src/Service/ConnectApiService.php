<?php

namespace Lle\OAuthClientBundle\Service;

use Lle\OAuthClientBundle\Dto\Api\ProfileDto;
use Lle\OAuthClientBundle\Dto\Api\UserDto;

class ConnectApiService
{
    public function __construct(
        private ConnectApiClient $client
    ) {
    }

    public function get(int $id): array
    {
        return $this->client->call('/api/users/' . $id, 'GET');
    }

    public function new(array $payload): array
    {
        return $this->client->call('/api/users', 'POST', $payload);
    }

    public function edit(array $payload): array
    {
        return $this->client->call('/api/users', 'PUT', $payload);
    }

    public function delete(int $id): array
    {
        return $this->client->call('/api/users/' . $id, 'DELETE');
    }
}
