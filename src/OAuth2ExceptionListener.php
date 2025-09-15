<?php

namespace Lle\OAuthClientBundle;

use Lle\OAuthClientBundle\Exception\OAuth2Exception;
use Lle\OAuthClientBundle\Service\OAuth2Service;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * If there is an OAuth2 error (typically "Invalid state"),
 * we try to login again
 * After MAX_NUMBER_TRIES_ERROR_SESSION_KEY tries, we show the user a generic error page
 * (the page is inside Connect)
 */
class OAuth2ExceptionListener
{
    public const NUMBER_TRIES_ERROR_SESSION_KEY = 'lle_oauth2_client_number_tries_error';
    public const MAX_NUMBER_TRIES_ERROR_SESSION_KEY = 3;

    public function __construct(
        protected OAuth2Service $oauth2Service,
        protected ParameterBagInterface $parameters,
        protected UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof OAuth2Exception) {
            return;
        }

        $request = $event->getRequest();
        $session = $request->getSession();

        $nbTries = $session->get(self::NUMBER_TRIES_ERROR_SESSION_KEY, 0);
        $nbTries++;

        if ($nbTries > static::MAX_NUMBER_TRIES_ERROR_SESSION_KEY) {
            /** @var string $oauthPublicUrl */
            $oauthPublicUrl = $this->parameters->get('lle.oauth_client.domain');

            $retryUrl = $this->urlGenerator->generate(
                'login',
                referenceType: UrlGeneratorInterface::ABSOLUTE_URL
            );

            // if there are too many attemps, we redirect to a generic error page
            $event->setResponse(
                new RedirectResponse($oauthPublicUrl . 'login-failure?retry_url=' . $retryUrl)
            );

            $session->remove(self::NUMBER_TRIES_ERROR_SESSION_KEY);

            return;
        }

        $session->set(self::NUMBER_TRIES_ERROR_SESSION_KEY, $nbTries);

        $event->setResponse($this->oauth2Service->authorize($request));
    }
}
