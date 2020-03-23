<?php

namespace Lle\OAuthClientBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

class OAuthClientExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter( 'lle.oauth_client.domain', $config[ 'domain' ] );
        $container->setParameter( 'lle.oauth_client.apiconnect', $config[ 'apiconnect' ] );
        $container->setParameter( 'lle.oauth_client.default_user', $config[ 'default_user' ] );
        $container->setParameter( 'lle.oauth_client.default_password', $config[ 'default_password' ] );
        $container->setParameter( 'lle.oauth_client.class_user', $config[ 'class_user' ] );
        $container->setParameter( 'lle.oauth_client.token_name', $config[ 'token_name' ] );
        $container->setParameter( 'lle.oauth_client.header_token_name', $config[ 'header_token_name' ] );
        $container->setParameter( 'lle.oauth_client.token_type', $config[ 'token_type' ] );
        $container->setParameter( 'lle.oauth_client.token_name_field', $config[ 'token_name_field' ] );
        $container->setParameter( 'lle.oauth_client.key_field', $config[ 'key_field' ] );
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
        $loader->load('form.yaml');
    }

    public function getAlias()
    {
        return 'lle_oauth_client';
    }
}
