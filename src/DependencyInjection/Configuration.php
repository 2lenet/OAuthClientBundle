<?php

namespace Lle\OAuthClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('lle_oauth_client');

        $rootNode
            ->children()
                    ->scalarNode('client_id')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('client_secret')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('server_domain')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
