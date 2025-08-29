<?php

namespace Lle\OAuthClientBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class OAuthClientExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('lle.oauth_client.domain', $config['domain']);
        $container->setParameter('lle.oauth_client.apiconnect', $config['apiconnect']);
        $container->setParameter('lle.oauth_client.client_id', $config['client_id']);
        $container->setParameter('lle.oauth_client.client_secret', $config['client_secret']);
        $container->setParameter('lle.oauth_client.redirect_route', $config['redirect_route']);

        $container->setParameter('lle.oauth_client.api_user', $config['api_user']);
        $container->setParameter('lle.oauth_client.api_password', $config['api_password']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function getAlias(): string
    {
        return 'lle_oauth_client';
    }
}
