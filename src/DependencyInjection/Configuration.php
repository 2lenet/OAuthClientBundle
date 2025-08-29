<?php

namespace Lle\OAuthClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
            ->scalarNode('class_user')->defaultValue('App\Entity\User')->setDeprecated('2lenet/oauth-client-bundle', '3.1.0')->end()
            ->scalarNode('token_name')->defaultValue('token')->setDeprecated('2lenet/oauth-client-bundle', '3.1.0')->end()
            ->scalarNode('header_token_name')->defaultValue('Authorization')->setDeprecated('2lenet/oauth-client-bundle', '3.1.0')->end()
            ->scalarNode('token_type')->defaultValue(null)->setDeprecated('2lenet/oauth-client-bundle', '3.1.0')->end()
            ->scalarNode('key_field')->defaultValue('idConnect')->setDeprecated('2lenet/oauth-client-bundle', '3.1.0')->end()
            ->scalarNode('token_name_field')->defaultValue('token')->setDeprecated('2lenet/oauth-client-bundle', '3.1.0')->end();

        return $treeBuilder;
    }
}
