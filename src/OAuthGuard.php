<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OAuthGuard.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd;

use Illuminate\Http\Request;
use League\OAuth2\Server\ResourceServer;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

class OAuthGuard {

    protected $resourceServer;

    protected $factory;

    public function __construct(ResourceServer $resourceServer) {
        $this->resourceServer = $resourceServer;
        $this->factory        = new DiactorosFactory();
    }

    public function user(Request $request) {

        $request        = $this->factory->createRequest($request);
        $authValidation = $this->resourceServer->validateAuthenticatedRequest($request);





    }

}
