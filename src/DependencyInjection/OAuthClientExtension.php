<?php

namespace Lle\OAuthClientBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OAuthClientExtension extends ConfigurableExtension
{

    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return "lle_oauth_client";
    }
}
