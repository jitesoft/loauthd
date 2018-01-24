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
            => Jitesoft\Loauthd\Repositories\Doctrine\AccessTokenRepository::class,
        League\OAuth2\Server\Repositories\ClientRepositoryInterface::class
            => Jitesoft\Loauthd\Repositories\Doctrine\ClientRepository::class,
        League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface::class
            => Jitesoft\Loauthd\Repositories\Doctrine\RefreshTokenRepository::class,
        League\OAuth2\Server\Repositories\ScopeRepositoryInterface::class
            => Jitesoft\Loauthd\Repositories\Doctrine\ScopeRepository::class,
        League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface::class
            => Jitesoft\Loauthd\Repositories\Doctrine\ScopeRepository::class,
        Jitesoft\Loauthd\Repositories\Doctrine\Contracts\UserRepositoryInterface::class
            => Jitesoft\Loauthd\Repositories\Doctrine\UserRepository::class
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
    /*
    |--------------------------------------------------------------------------
    | User identification key.
    |--------------------------------------------------------------------------
    |
    | Key to use when fetching an existing user from the user repository.
    | The user_identification will be the key of the entity that the repository
    | searches for. Could be an email, username, identifier or anything.
    |
    */
    'user_identification' => 'userName',
    /*
    |--------------------------------------------------------------------------
    | Password hash.
    |--------------------------------------------------------------------------
    |
    | Hash implementation to use when verifying passwords.
    |
    */
    'password_hash'       => Illuminate\Hashing\BcryptHasher::class,
    /*
    |--------------------------------------------------------------------------
    | Scope validator.
    |--------------------------------------------------------------------------
    |
    | Class which validates the scopes on auth requests.
    | If you do not use scopes, default implementation can be used, else
    | implement your own and bind it here.
    |
    */
    Jitesoft\Loauthd\Contracts\ScopeValidatorInterface::class => Jitesoft\Loauthd\ScopeValidator::class
];
