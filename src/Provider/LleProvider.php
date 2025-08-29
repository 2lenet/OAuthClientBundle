<?php

namespace Lle\OAuthClientBundle\Provider;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;

class LleProvider extends GenericProvider
{
    use BearerAuthorizationTrait;

    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new LleResourceOwner($response);
    }
}
