<?php

return [
    'encryption_key' => env('OAUTH_AUTHORIZATION_KEY', ''),
    /*
    |--------------------------------------------------------------------------
    | Repositories.
    |--------------------------------------------------------------------------
    |
    | Override the repositories that the models are fetched from.
    | The default implementations uses the $entityManager->getRepository(entity)
    | methods to fetch the given objects.
    |
    | Implementations must implement the interfaces they bind to.
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
        Jitesoft\OAuth\Lumen\Repositories\Doctrine\Contracts\UserRepositoryInterface::class
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
