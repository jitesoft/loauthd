<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OAuth2Middleware.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jitesoft\Exceptions\Security\OAuth2\OAuth2Exception;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Zend\Diactoros\ServerRequestFactory;

class OAuth2Middleware {

    protected $resourceServer;

    public function __construct(ResourceServer $resourceServer) {
        $this->resourceServer = $resourceServer;
    }

    public function handle(Request $request, Closure $next) {
        try {
            // Get the PSR7 request from globals.
            $this->resourceServer->validateAuthenticatedRequest(ServerRequestFactory::fromGlobals());
        } catch (OAuthServerException $exception) {
            return new OAuth2Exception($exception->getMessage(), $exception->getHttpStatusCode(), $exception);
        }

        return $next($request);
    }

}
