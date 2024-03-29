<?php

namespace Lle\OAuthClientBundle\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;

use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;

use Lle\OAuthClientBundle\Provider\LleResourceOwner;

class LleProvider extends GenericProvider
{
    use BearerAuthorizationTrait;
/*
    public $domain;
    public $apiconnect;

    public function __construct(array $options, array $collaborators = [])
    {
        $this->domain = $options['domain'];
        $this->apiconnect = $options['apiconnect'] ?? $this->domain;

        parent::__construct($options, $collaborators);
    }

    public function getBaseAuthorizationUrl()
    {
        return $this->domain . "2le-auth";
    }

    public function getBaseAccessTokenUrl(array $params = [])
    {
        return $this->apiconnect . "oauth/v2/token";
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->apiconnect . 'user-details?token=' . $token->getToken();
    }

    protected function getDefaultScopes()
    {
        return [];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400 || isset($data['error'])) {
            throw new IdentityProviderException($data['error'], $response->getStatusCode(), $response);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new LleResourceOwner($response, $token);
    }*/
}
