<?php

return [
    'encryption_key' => env('OAUTH_AUTHORIZATION_KEY', ''),

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
        League\OAuth2\Server\Entities\AccessTokenEntityInterface::class
            => Jitesoft\OAuth\Lumen\Entities\AccessToken::class,
        League\OAuth2\Server\Entities\ClientEntityInterface::class
            => Jitesoft\OAuth\Lumen\Entities\Client::class,
        League\OAuth2\Server\Entities\RefreshTokenEntityInterface::class
            => Jitesoft\OAuth\Lumen\Entities\RefreshToken::class,
        League\OAuth2\Server\Entities\ScopeEntityInterface::class
            => Jitesoft\OAuth\Lumen\Entities\Scope::class,
        League\OAuth2\Server\Entities\AuthCodeEntityInterface::class
            => Jitesoft\OAuth\Lumen\Entities\AuthCode::class,
        League\OAuth2\Server\Entities\UserEntityInterface::class
            => Jitesoft\OAuth\Lumen\Entities\User::class
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
        League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface::class
            => Jitesoft\OAuth\Lumen\Repositories\Doctrine\AccessTokenRepository::class,
        League\OAuth2\Server\Repositories\ClientRepositoryInterface::class
            => Jitesoft\OAuth\Lumen\Repositories\Doctrine\ClientRepository::class,
        League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface::class
            => Jitesoft\OAuth\Lumen\Repositories\Doctrine\RefreshTokenRepository::class,
        League\OAuth2\Server\Repositories\ScopeRepositoryInterface::class
            => Jitesoft\OAuth\Lumen\Repositories\Doctrine\ScopeRepository::class,
        League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface::class
            => Jitesoft\OAuth\Lumen\Repositories\Doctrine\ScopeRepository::class,
        League\OAuth2\Server\Repositories\UserRepositoryInterface::class
            => Jitesoft\OAuth\Lumen\Repositories\Doctrine\UserRepository::class
    ],
    /*
    |--------------------------------------------------------------------------
    | Grant types.
    |--------------------------------------------------------------------------
    |
    | Grant types available to use.
    | If one is not wanted, just comment it out and it will not be loaded
    | in to the auth server.
    |
    */
    'grant_types' => [
        'AuthCode'          => League\OAuth2\Server\Grant\AuthCodeGrant::class,
        'RefreshToken'      => League\OAuth2\Server\Grant\RefreshTokenGrant::class,
        'Password'          => League\OAuth2\Server\Grant\PasswordGrant::class,
        'Implicit'          => League\OAuth2\Server\Grant\ImplicitGrant::class,
        'ClientCredentials' => League\OAuth2\Server\Grant\ClientCredentialsGrant::class
    ],
    /*
    |--------------------------------------------------------------------------
    | Token lifetime.
    |--------------------------------------------------------------------------
    |
    | Lifetime of the auth tokens.
    | Change to preferred lifetime.
    |
    */
    'token_ttl'           => Carbon\Carbon::now()->addHour(1),
    'user_identification' => 'userName',
    'password_hash'       => Illuminate\Hashing\BcryptHasher::class
];
