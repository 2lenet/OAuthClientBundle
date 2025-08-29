<?php

namespace Lle\OAuthClientBundle;

use Lle\OAuthClientBundle\DependencyInjection\OAuthClientExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OAuthClientBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new OAuthClientExtension();
    }

}
