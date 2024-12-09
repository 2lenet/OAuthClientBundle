<?php

namespace Lle\OAuthClientBundle\Service;

use Lle\OAuthClientBundle\Exception\ConnectException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ConnectApiClient
{
    public function __construct(
        protected ParameterBagInterface $parameters,
        protected HttpClientInterface $httpClient,
    ) {
    }

    public function call(string $url, string $method, array $data = [])
    {
        $response = $this->getClient()->request($method, $url, [
            'json' => $data,
        ]);

        if ($response->getStatusCode() === 404) {
            throw new ConnectException('Not Found', ConnectException::NOT_FOUND);
        }
        
        return json_decode($response->getContent(false), true);
    }

    protected function getClient(): HttpClientInterface
    {
        $baseUri = $this->parameters->get('lle.oauth_client.apiconnect');
        $user = $this->parameters->get('lle.oauth_client.api_user');
        $password = $this->parameters->get('lle.oauth_client.api_password');

        return $this->httpClient->withOptions([
            'base_uri' => $baseUri,
            'auth_basic' => [$user, $password]
        ]);
    }
}
