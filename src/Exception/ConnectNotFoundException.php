<?php

namespace Lle\OAuthClientBundle\Exception;

class ConnectNotFoundException extends ConnectException
{
    public function __construct()
    {
        parent::__construct('Not Found', ConnectException::NOT_FOUND);
    }
}
