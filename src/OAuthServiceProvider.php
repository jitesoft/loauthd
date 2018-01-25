<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OAuthServiceProvider.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd;

use Illuminate\Auth\AuthManager;
use Illuminate\Auth\RequestGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
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
    /** @var $this->app Application */

    /** @var  AuthorizationServer */
    protected $authServer;

    public function register() {
        if (config(OAuth::CONFIG_NAMESPACE, null) === null) {
            $this->app->configure(OAuth::CONFIG_NAMESPACE);
        }

        // Bind all important interfaces.

        $this->app->bind(ScopeValidatorInterface::class, config(
            OAuth::CONFIG_NAMESPACE. '.scope_validator',
            ScopeValidator::class
        ));

        $repositories = config(OAuth::CONFIG_NAMESPACE. '.repositories', []);
        foreach (array_merge($repositories) as $interface => $class) {
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

        $this->app->routeMiddleware([
            'auth:oauth' => OAuth2Middleware::class
        ]);

        $this->registerGuard($this->app->make(AuthManager::class));
    }

    public function boot() {
        if ($this->app->runningInConsole()) {
            $this->commands([
                KeyGenerateCommand::class
            ]);
        }
    }

    protected function registerGuard(AuthManager $authManager) {
        $authManager->extend('loauthd', function() {

            $guard = new RequestGuard(function($request) {
                $oauthGuard = new OAuthGuard(
                    $this->app->make(ResourceServer::class)
                );
                $oauthGuard->user($request);
                return $oauthGuard;
            }, $this->app['request']);

            $this->app->refresh('request', $guard, 'setRequest');
            return $guard;
        });
    }

    protected function registerGrants(AuthorizationServer $server) {
        $this->makeGrant($server, config(OAuth::CONFIG_NAMESPACE. '.grant_types.AuthCode', null), [
            $this->app->make(AuthCodeRepositoryInterface::class),
            $this->app->make(RefreshTokenRepositoryInterface::class),
            config(OAuth::CONFIG_NAMESPACE. '.token_ttl')
        ]);

        $this->makeGrant($server, config(OAuth::CONFIG_NAMESPACE. '.grant_types.RefreshToken', null), [
            $this->app->make(RefreshTokenRepositoryInterface::class)
        ]);

        $this->makeGrant($server, config(OAuth::CONFIG_NAMESPACE. '.grant_types.Password', null), [
            $this->app->make(UserRepositoryInterface::class),
            $this->app->make(RefreshTokenRepositoryInterface::class)
        ]);

        $this->makeGrant($server, config(OAuth::CONFIG_NAMESPACE. '.grant_types.Implicit', null), [
            config(OAuth::CONFIG_NAMESPACE. '.token_ttl')
        ]);

        $this->makeGrant($server, config(OAuth::CONFIG_NAMESPACE. '.grant_types.ClientCredentials', null), []);
    }

    protected function makeGrant(AuthorizationServer $server, ?string $type, array $args) {
        if ($type !== null) {
            $grant = new $type(...$args);
            if ($type !== null && $grant !== null) {
                $server->enableGrantType($grant);
            }
        }
    }

    protected function createAuthServer(): AuthorizationServer {
        $keyPath = config(OAuth::CONFIG_NAMESPACE. '.key_path', storage_path('oauth'));
        return new AuthorizationServer(
            $this->app->make(ClientRepositoryInterface::class),
            $this->app->make(AccessTokenRepositoryInterface::class),
            $this->app->make(ScopeRepositoryInterface::class),
            new CryptKey($keyPath . '/private.key'),
            config(OAuth::CONFIG_NAMESPACE. '.encryption_key')
        );
    }

    protected function createResourceServer(): ResourceServer {
        $keyPath = config(OAuth::CONFIG_NAMESPACE. '.key_path', storage_path('oauth'));
        return new ResourceServer(
            $this->app->make(AccessTokenRepositoryInterface::class),
            new CryptKey($keyPath . '/public.key')
        );
    }

}
