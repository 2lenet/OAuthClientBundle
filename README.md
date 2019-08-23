# OAuthClientBundle

OAuth client bundle for 2le.

## Installation

```composer require 2lenet/oauth-client-bundle```

## Configuration

Add this to `packages/security.yaml`.

```yaml
security:
    providers:
        main:
            id: lle_oauth_user_provider
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            pattern: ^/
            provider: main
            anonymous: ~
            form_login:
                login_path: login
                check_path: login_check
            logout:
                path: logout
                target: logout_oauth
            guard:
                authenticators:
                    - lle_oauth_guard
                    - lle_oauth_token
                entry_point: lle_oauth_token

    access_control:
    - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
    - { path: ^/logout_oauth$, role: IS_AUTHENTICATED_ANONYMOUSLY }
    - { path: ^/, role: ROLE_USER }

    role_hierarchy:
        ROLE_ADMIN: [ROLE_USER]
        ROLE_SUPER_ADMIN: [ROLE_ADMIN]
```

Add this to `routes.yaml`.

```yaml
logout:
    path: /logout

oauth_client_bundle:
    resource: "@OAuthClientBundle/Resources/config/routes.yaml"
    prefix:   /
```

Add this to `packages/oauth_clients.yaml`.

```yaml
knpu_oauth2_client:
    clients:
        2le_oauth:
            type: generic

            provider_class: Lle\OAuthClientBundle\Provider\LleProvider
            provider_options: {domain: '%env(DOMAIN)%'}

            client_id: '%env(CLIENT_ID)%'
            client_secret: '%env(CLIENT_SECRET)%'
            redirect_route: login_check

lle_oauth_client:
    domain: '%env(DOMAIN)%'
    class_user: 'App\Entity\User'
    header_token_name: 'Authorization' #header du token
    token_name: 'token' #nom du token (json)
    token_name_field: 'token' #nom du champ token de l'user
    key_field: 'idConnect' #nom du champ key de l'user
    token_type: ~ #type ou prefix du token (permet d'affiné Authenticator)
```

## TOKEN LOGIN WITH jSON

use the config header_token_name, token_name, token_name_field, key_field and token_type,
but the default value is ok.
However you have to implements Lle\OAuthClientBundle\Model\UserTokenInterface in your user class !


## env

Add this to `.env` and complete. Domain is the *server* domain. It should end with a slash.

```
###> 2lenet/OAuthClientBundle ###
CLIENT_ID=
CLIENT_SECRET=
DOMAIN=
OAUTHAPI_PASSWORD=
OAUTHAPI_USERNAME=
###< 2lenet/OAuthClientBundle ###

```

