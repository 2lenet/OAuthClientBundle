<?php

namespace Lle\OAuthClientBundle\Service;

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

    public function list(array $payload): array
    {
        return $this->client->call('/api/users', 'GET', $payload);
    }

    public function resetPassword(int $id): void
    {
        $this->client->call('/api/users/reset-password/' . $id, 'GET');
    }

    public function authenticatorQrCode(int $id): array
    {
        return $this->client->call('/api/users/authenticator-qr-code/' . $id, 'GET');
    }
}
