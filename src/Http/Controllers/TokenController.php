<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  TokenController.phpt of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use League\OAuth2\Server\AuthorizationServer;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Zend\Diactoros\Response;

class TokenController extends Controller {

    /** @var AuthorizationServer */
    protected $authorizationServer;

    /** @var DiactorosFactory */
    protected $factory;

    public function __construct(AuthorizationServer $authorizationServer) {
        $this->authorizationServer = $authorizationServer;
        $this->factory             = new DiactorosFactory();
    }


    public function postRefreshTransientToken() {}

    public function postIssueAccessToken(Request $request) {
        $psrRequest = $this->factory->createRequest($request);

        $out = $this->authorizationServer->respondToAccessTokenRequest($psrRequest, new Response());
        return new \Illuminate\Http\Response(
            $out->getBody(),
            $out->getStatusCode(),
            $out->getHeaders()
        );
    }

    public function deleteAccessToken(Request $request) {
        $request->get('token');
    }

}
