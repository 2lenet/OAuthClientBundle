<?php

namespace Lle\OAuthClientBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OAuthClientExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('lle_oauth_client.config', $config);
    }

    /**
     * We don't want Symfony to call it o_auth_client.
     * @return string
     */
    public function getAlias()
    {
        return "lle_oauth_client";
    }
}
