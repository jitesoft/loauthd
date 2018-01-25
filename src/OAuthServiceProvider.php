<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OAuthServiceProvider.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd;

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
        $entities     = config(OAuth::CONFIG_NAMESPACE. '.entities', []);
        $repositories = config(OAuth::CONFIG_NAMESPACE. '.repositories', []);

        $this->app->bind(ScopeValidatorInterface::class, config(
            OAuth::CONFIG_NAMESPACE. '.scope_validator',
            ScopeValidator::class
        ));

        foreach (array_merge($entities, $repositories) as $interface => $class) {
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

        $this->app->routeMiddleware(OAuth2Middleware::class);
    }

    public function boot() {
        if ($this->app->runningInConsole()) {
            $this->commands([
                KeyGenerateCommand::class
            ]);
        }
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
            UserRepositoryInterface::class,
            RefreshTokenRepositoryInterface::class
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
        return new AuthorizationServer(
            $this->app->make(ClientRepositoryInterface::class),
            $this->app->make(AccessTokenRepositoryInterface::class),
            $this->app->make(ScopeRepositoryInterface::class),
            new CryptKey(storage_path('/oauth/private.key')),
            config(OAuth::CONFIG_NAMESPACE. '.encryption_key')
        );
    }

    protected function createResourceServer(): ResourceServer {
        return new ResourceServer(
            $this->app->make(AccessTokenRepositoryInterface::class),
            new CryptKey(storage_path('/oauth/public.key'))
        );
    }

}
