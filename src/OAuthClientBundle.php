<?php
namespace Lle\OAuthClientBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Lle\OAuthClientBundle\DependencyInjection\OAuthClientExtension;

class OAuthClientBundle extends Bundle
{
    /**
     * So the alias isn't o_auth_client in the config files.
     * @return ExtensionInterface
     */
    public function getContainerExtension()
    {
        if ($this->extension === null) {
            return new OAuthClientExtension();
        }

        return $this->extension;
    }
}
