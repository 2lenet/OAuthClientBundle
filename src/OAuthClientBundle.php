<?php

namespace Lle\OAuthClientBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Lle\OAuthClientBundle\DependencyInjection\OAuthClientExtension;

class OAuthClientBundle extends Bundle
{

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new OAuthClientExtension();
    }

}
