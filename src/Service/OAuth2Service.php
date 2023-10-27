<?php

namespace Lle\OAuthClientBundle\Service;

use League\OAuth2\Client\Provider\GenericProvider;
use Lle\OAuthClientBundle\Exception\OAuth2Exception;
use Lle\OAuthClientBundle\Provider\LleProvider;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OAuth2Service
{
    public function __construct(
        private ParameterBagInterface $parameters,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function handle(Request $request)
    {
            try {

            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

                // Failed to get the access token or user details.
                dd($e->getMessage());
            }
    }

    public function check(Request $request): string
    {
        if ($request->query->get('error')) {
            throw new OAuth2Exception($request->query->get('message', $request->query->get('error')));
        }

        $code = $request->query->get('code');
        if (!$code) {
            throw new OAuth2Exception('No authorization code provided.');
        }

        $actualState = $request->query->get('state');
        $expectedState = $request->getSession()->get('oauth2state');

        if (!$actualState || !$expectedState || $actualState !== $expectedState) {
            $request->getSession()->remove('oauth2state');

            throw new OAuth2Exception('Invalid state.');
        }

        return $code;
    }

    public function fetchRessourceOwner(string $authorizationCode)
    {
        $provider = $this->getProvider();
        
        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $authorizationCode,
        ]);

        // Using the access token, we may look up details about the
        // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);
dd($resourceOwner);
        var_export($resourceOwner->toArray());

        // The provider provides a way to get an authenticated API request for
        // the service, using the access token; it returns an object conforming
        // to Psr\Http\Message\RequestInterface.
        $request = $provider->getAuthenticatedRequest(
            'GET',
            'https://service.example.com/resource',
            $accessToken
        );
    }

    public function authorize(Request $request): RedirectResponse
    {
        $provider = $this->getProvider();
        $session = $request->getSession();

        // Fetch the authorization URL from the provider; this returns the
        // urlAuthorize option and generates and applies any necessary parameters
        // (e.g. state).
        $authorizationUrl = $provider->getAuthorizationUrl();

        // Get the state generated for you and store it to the session.
        $session->set('oauth2state', $provider->getState());

        // Optional, only required when PKCE is enabled.
        // Get the PKCE code generated for you and store it to the session.
        $session->set('oauth2pkceCode', $provider->getPkceCode());

        // Redirect the user to the authorization URL.
        return new RedirectResponse($authorizationUrl);
    }

    private function getProvider(): GenericProvider
    {
        $clientId = $this->parameters->get('lle.oauth_client.client_id');
        $clientSecret = $this->parameters->get('lle.oauth_client.client_secret');
        $redirectRoute = $this->parameters->get('lle.oauth_client.redirect_route');
        $domain = $this->parameters->get('lle.oauth_client.domain');
        $api = $this->parameters->get('lle.oauth_client.apiconnect');

        $redirectUri = $this->urlGenerator->generate(
            $redirectRoute,
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new LleProvider([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri,
            'urlAuthorize' => $domain . 'authorize',
            'urlAccessToken' => $api . 'token',
            'urlResourceOwnerDetails' => $api . 'user-details'
        ]);
    }
}
