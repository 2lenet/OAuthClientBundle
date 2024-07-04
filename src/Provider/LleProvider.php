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

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new LleResourceOwner($response);
    }
}
