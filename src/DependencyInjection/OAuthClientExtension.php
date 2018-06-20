<?php

namespace Lle\OAuthClientBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

class OAuthClientExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        // read yaml from knpu oauth2 client
        // $config_path = __DIR__ . '/../Resources/config/knpu_oauth2_client_config.yaml';
        // $yaml = (new Parser())->parse(file_get_contents($config_path));
        // put it in symfony

        // SERVICES
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }
}
