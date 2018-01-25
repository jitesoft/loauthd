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
        Jitesoft\Loauthd\Repositories\Doctrine\Contracts\AccessTokenRepositoryInterface::class
            => Jitesoft\Loauthd\Repositories\Doctrine\AccessTokenRepository::class,
        Jitesoft\Loauthd\Repositories\Doctrine\Contracts\ClientRepositoryInterface::class
            => Jitesoft\Loauthd\Repositories\Doctrine\ClientRepository::class,
        Jitesoft\Loauthd\Repositories\Doctrine\Contracts\RefreshTokenRepositoryInterface::class
            => Jitesoft\Loauthd\Repositories\Doctrine\RefreshTokenRepository::class,
        Jitesoft\Loauthd\Repositories\Doctrine\Contracts\ScopeRepositoryInterface::class
            => Jitesoft\Loauthd\Repositories\Doctrine\ScopeRepository::class,
        Jitesoft\Loauthd\Repositories\Doctrine\Contracts\AuthCodeRepositoryInterface::class
            => Jitesoft\Loauthd\Repositories\Doctrine\AuthCodeRepository::class,
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
        'AuthCode'          => Jitesoft\Loauthd\Grants\AuthCode::class,
        'RefreshToken'      => Jitesoft\Loauthd\Grants\RefreshToken::class,
        'Password'          => Jitesoft\Loauthd\Grants\Password::class,
        'Implicit'          => Jitesoft\Loauthd\Grants\Implicit::class,
        'ClientCredentials' => Jitesoft\Loauthd\Grants\ClientCredentials::class,
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
    'token_ttl' => Carbon\CarbonInterval::create(0, 0, 0, 0, 1, 0, 0),
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
    'user_identification' => env('OAUTH_IDENTIFICATION', 'userName'),
    /*
    |--------------------------------------------------------------------------
    | Password hash.
    |--------------------------------------------------------------------------
    |
    | Hash implementation to use when verifying passwords.
    |
    */
    'password_hash' => Illuminate\Hashing\BcryptHasher::class,
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
    Jitesoft\Loauthd\Contracts\ScopeValidatorInterface::class => Jitesoft\Loauthd\ScopeValidator::class,
    /*
    |--------------------------------------------------------------------------
    | User class.
    |--------------------------------------------------------------------------
    |
    | If you use another user class than the default provided by framework
    | it can be changed here.
    |
    */
    'user_model' => Jitesoft\Loauthd\Entities\User::class,
    /*
    |--------------------------------------------------------------------------
    | Key path.
    |--------------------------------------------------------------------------
    |
    | Path to the directory where the public and private keys used by the
    | oauth service are.
    |
    */
    'key_path' => env('OAUTH_KEYS_PATH', storage_path('oauth')),
];
