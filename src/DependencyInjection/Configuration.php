<?php

namespace Lle\OAuthClientBundle\DependencyInjection;

use App\Entity\User;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('lle_oauth_client');
        $rootNode
            ->children()
            ->scalarNode('domain')->defaultValue(getenv('DOMAIN'))->setDeprecated()->end()
            ->scalarNode('lle_oauth_domain')->defaultValue(null)->end()
            ->scalarNode('default_user')->defaultValue('tmpuser')->end()
            ->scalarNode('default_password')->defaultValue('tmppassword')->end()
            ->scalarNode('class_user')->defaultValue('App\Entity\User')->end()
            ->scalarNode('token_name')->defaultValue('token')->end()
            ->scalarNode('header_token_name')->defaultValue('Authorization')->end()
            ->scalarNode('token_type')->defaultValue(null)->end()
            ->scalarNode('key_field')->defaultValue('idConnect')->end()
            ->scalarNode('token_name_field')->defaultValue('token')->end();

        return $treeBuilder;
    }
}
