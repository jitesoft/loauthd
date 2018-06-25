<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OAuthServiceProvider.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd;

use Illuminate\Auth\AuthManager;
use Illuminate\Auth\RequestGuard;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Jitesoft\Exceptions\Database\Entity\EntityException;
use Jitesoft\Exceptions\Security\OAuth2\OAuth2Exception;
use Jitesoft\Loauthd\Commands\KeyGenerateCommand;
use Jitesoft\Loauthd\Contracts\ScopeValidatorInterface;
use Jitesoft\Loauthd\Http\Middleware\OAuth2Middleware;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\AccessTokenRepositoryInterface;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\AuthCodeRepositoryInterface;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\ClientRepositoryInterface;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\RefreshTokenRepositoryInterface;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\ScopeRepositoryInterface;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\UserRepositoryInterface;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\ResourceServer;

/**
 * Class OAuthServiceProvider
 */
class OAuthServiceProvider extends ServiceProvider {
    public const CONFIG_NAMESPACE = 'loauthd';

    /** @var  AuthorizationServer */
    protected $authServer;

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getConfig(string $key, $default = null) {
        return config(sprintf('%s.%s', static::CONFIG_NAMESPACE, $key), $default);
    }

    public function register() {
        if (config(static::CONFIG_NAMESPACE, null) === null) {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->app->configure(static::CONFIG_NAMESPACE);
        }

        $this->app->bind(ScopeValidatorInterface::class, config(
            $this->getConfig('scope_validator'),
            ScopeValidator::class
        ));

        $repositories = $this->getConfig('repositories', []);
        foreach ($repositories as $interface => $class) {
            $this->app->bind($interface, $class);
        }

        $this->app->singleton(AuthorizationServer::class, function() {
            $server = $this->createAuthServer();
            $this->registerGrants($server);
            return $server;
        });

        $this->app->singleton(ResourceServer::class, function() {
            return $this->createResourceServer();
        });

        /** @noinspection PhpUndefinedMethodInspection */
        $this->app->routeMiddleware([
            'auth:oauth' => OAuth2Middleware::class
        ]);

        $this->registerGuard($this->app->make('auth'));
    }

    public function boot() {
        if ($this->app->runningInConsole()) {
            $this->commands([
                KeyGenerateCommand::class
            ]);
        }
    }

    /**
     * @param AuthManager $authManager
     * @param Request     $request
     * @param array       $config
     * @return OAuthGuard
     * @throws EntityException
     * @throws OAuth2Exception
     */
    protected function createOauthGuard(AuthManager $authManager, Request $request, array $config) {
        $oauthGuard = new OAuthGuard(
            $this->app->make(ResourceServer::class),
            $authManager->createUserProvider($config['provider']),
            $this->app->make(UserRepositoryInterface::class),
            $this->app->make(ClientRepositoryInterface::class),
            $this->app->make(AccessTokenRepositoryInterface::class),
            $request
        );
        $oauthGuard->user();
        return $oauthGuard;
    }

    protected function registerGuard(AuthManager $authManager) {
        $authManager->extend('loauthd', function($app, $name, $config) use($authManager) {
            $guard = new RequestGuard(function($request) use($authManager, $config) {
                return $this->createOauthGuard($authManager, $request, $config);
            }, $app['request']);

            /** @noinspection PhpUndefinedMethodInspection */
            $this->app->refresh('request', $guard, 'setRequest');
            return $guard;
        });
    }

    protected function registerGrants(AuthorizationServer $server) {
        $this->makeGrant($server, $this->getConfig('grant_types.AuthCode', null), [
            $this->app->make(AuthCodeRepositoryInterface::class),
            $this->app->make(RefreshTokenRepositoryInterface::class),
            $this->getConfig('token_ttl')
        ]);

        $this->makeGrant($server, $this->getConfig('grant_types.RefreshToken', null), [
            $this->app->make(RefreshTokenRepositoryInterface::class)
        ]);

        $this->makeGrant($server, $this->getConfig('grant_types.Password', null), [
            $this->app->make(UserRepositoryInterface::class),
            $this->app->make(RefreshTokenRepositoryInterface::class)
        ]);

        $this->makeGrant($server, $this->getConfig('grant_types.Implicit', null), [
            $this->getConfig('token_ttl')
        ]);

        $this->makeGrant($server, $this->getConfig('grant_types.ClientCredentials', null), []);
    }

    protected function makeGrant(AuthorizationServer $server, ?string $type, array $args) {
        if ($type !== null) {
            $grant = new $type(...$args);
            if ($type !== null && $grant !== null) {
                $server->enableGrantType($grant);
            }
        }
    }

    protected function getKey(string $key): string {
        return sprintf(
            '%s/%s.key',
            $this->getConfig('key_path', storage_path('oauth')),
            $key
        );
    }

    protected function createAuthServer(): AuthorizationServer {
        return new AuthorizationServer(
            $this->app->make(ClientRepositoryInterface::class),
            $this->app->make(AccessTokenRepositoryInterface::class),
            $this->app->make(ScopeRepositoryInterface::class),
            new CryptKey($this->getKey('private')),
            $this->getConfig('encryption_key')
        );
    }

    protected function createResourceServer(): ResourceServer {
        return new ResourceServer(
            $this->app->make(AccessTokenRepositoryInterface::class),
            new CryptKey($this->getKey('public'))
        );
    }

}
