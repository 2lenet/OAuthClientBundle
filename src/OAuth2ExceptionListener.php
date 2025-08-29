<?php

namespace Lle\OAuthClientBundle;

use Lle\OAuthClientBundle\Exception\OAuth2Exception;
use Lle\OAuthClientBundle\Service\OAuth2Service;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * S'il y a une erreur OAuth2 (typiquement Invalid state ou quand on fait des F5),
 * on essaie de refaire un login
 * Au bout de MAX_NUMBER_TRIES_ERROR_SESSION_KEY tentatives on affiche un message d'erreur générique
 * (le message d'erreur est une page dans Connect)
 */
class OAuth2ExceptionListener
{
    public const string NUMBER_TRIES_ERROR_SESSION_KEY = 'lle_oauth2_client_number_tries_error';
    public const int MAX_NUMBER_TRIES_ERROR_SESSION_KEY = 3;

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

            // rediriger sur logout avec redirection 'erreur'
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
