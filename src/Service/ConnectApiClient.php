<?php

namespace Lle\OAuthClientBundle\Service;

use Lle\OAuthClientBundle\Exception\ConnectException;
use Lle\OAuthClientBundle\Exception\ConnectNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ConnectApiClient
{
    public function __construct(
        protected ParameterBagInterface $parameters,
        protected HttpClientInterface $httpClient,
    ) {
    }

    public function call(string $url, string $method, array $data = [])
    {
        $options = [];
        if ($method === 'GET') {
            $options['query'] = $data;
        } else {
            $options['json'] = $data;
        }
        $response = $this->getClient()->request($method, $url, $options);

        $this->checkErrors($response);

        return json_decode($response->getContent(false), true);
    }

    protected function checkErrors(ResponseInterface $response): void
    {
        if ($response->getStatusCode() === 404) {
            throw new ConnectNotFoundException();
        }

        if ($response->getStatusCode() !== 200) {
            $errorJson = $response->getContent(false);

            if (json_validate($errorJson)) {
                $error = json_decode($errorJson, true);

                $code = $error['code'] ?? ConnectException::UNKNOWN_ERROR;

                if ($code === ConnectException::NOT_FOUND) {
                    throw new ConnectNotFoundException();
                }

                $message = $error['message'] ?? 'Unknown error';
                $data = $error['data'] ?? [];

                $exception = new ConnectException($message, $code);
                $exception->setData($data);

                throw $exception;
            } else {
                $message = 'Unknown error';
                $hint = strstr($errorJson, "\n", true);
                if (str_starts_with($hint, '<!--')) {
                    // Symfony on dev mode prints the exception in the first line
                    $message .= ' (hint: ' . $hint . ')';
                }
                $exception = new ConnectException($message, ConnectException::UNKNOWN_ERROR);
                $exception->setData(['response' => $errorJson]);

                throw $exception;
            }
        }
    }

    protected function getClient(): HttpClientInterface
    {
        $baseUri = $this->parameters->get('lle.oauth_client.apiconnect');
        $user = $this->parameters->get('lle.oauth_client.api_user');
        $password = $this->parameters->get('lle.oauth_client.api_password');

        return $this->httpClient->withOptions([
            'base_uri' => $baseUri,
            'auth_basic' => [$user, $password],
        ]);
    }
}
