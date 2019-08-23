<?php

namespace Lle\OAuthClientBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Lle\OAuthClientBundle\DependencyInjection\OAuthClientExtension;

final class OAuthEvent
{
    const onLoginJsonUser = 'lle.oauth.login_json_user';
}
