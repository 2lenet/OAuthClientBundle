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
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('lle_oauth_client');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()

            // Client
	        ->scalarNode('domain')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('apiconnect')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('client_id')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('client_secret')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('redirect_route')->isRequired()->cannotBeEmpty()->end()

            // API
            ->scalarNode('api_user')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('api_password')->isRequired()->cannotBeEmpty()->end()

            // JSON login
            ->scalarNode('class_user')->defaultValue('App\Entity\User')->end()
            ->scalarNode('token_name')->defaultValue('token')->end()
            ->scalarNode('header_token_name')->defaultValue('Authorization')->end()
            ->scalarNode('token_type')->defaultValue(null)->end()
            ->scalarNode('key_field')->defaultValue('idConnect')->end()
            ->scalarNode('token_name_field')->defaultValue('token')->end();

        return $treeBuilder;
    }
}
