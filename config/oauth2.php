<?php

return [
    'auth_key' => env('OAUTH_AUTHORIZATION_KEY', ''),



    /*
    |--------------------------------------------------------------------------
    | Entities.
    |--------------------------------------------------------------------------
    |
    | Define the entities that the repositories will fetch.
    | They must implement the given interfaces from League/OAuth2.
    |
    | The default entities are doctrine entities.
    | Recommendation is to change the User entity to your own user models.
    */
    'entities' => [
        'AccessToken'  => Jitesoft\OAuth\Lumen\Entities\AccessToken::class,
        'Client'       => Jitesoft\OAuth\Lumen\Entities\Client::class,
        'RefreshToken' => Jitesoft\OAuth\Lumen\Entities\RefreshToken::class,
        'Scope'        => Jitesoft\OAuth\Lumen\Entities\Scope::class,
        'AuthCode'     => Jitesoft\OAuth\Lumen\Entities\AuthCode::class,
        'User'         => Jitesoft\OAuth\Lumen\Entities\User::class
    ],
    /*
    |--------------------------------------------------------------------------
    | Repositories.
    |--------------------------------------------------------------------------
    |
    | Override the repositories that the models are fetched from.
    | The default implementations uses the $entityManager->getRepository(entity)
    | methods to fetch the given objects.
    |
    | Implementations must implement the interfaces from League/OAuth2.
    |
    */
    'repositories' => [
        'AccessTokenRepository'  => Jitesoft\OAuth\Lumen\Repositories\Doctrine\AccessTokenRepository::class,
        'ClientRepository'       => Jitesoft\OAuth\Lumen\Repositories\Doctrine\ClientRepository::class,
        'RefreshTokenRepository' => Jitesoft\OAuth\Lumen\Repositories\Doctrine\RefreshTokenRepository::class,
        'ScopeRepository'        => Jitesoft\OAuth\Lumen\Repositories\Doctrine\ScopeRepository::class,
        'AuthCodeRepository'     => Jitesoft\OAuth\Lumen\Repositories\Doctrine\ScopeRepository::class,
        'UserRepository'         => Jitesoft\OAuth\Lumen\Repositories\Doctrine\UserRepository::class
    ]

];
