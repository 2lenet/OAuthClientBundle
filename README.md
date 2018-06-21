# OAuthClientBundle

OAuth client bundle for 2le.

## Installation

(à remplir)

## Mise en place

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
        login:
            pattern: ^/login$
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
```

Add this to `.env` and complete. Domain should end with a slash.

```
###> OAuth ###
CLIENT_ID=
CLIENT_SECRET=
DOMAIN=
###< OAuth ###

```

