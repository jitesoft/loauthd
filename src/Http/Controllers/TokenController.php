<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  TokenController.phpt of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Http\Controllers;

use Illuminate\Http\Request;
use Jitesoft\Exceptions\Lazy\NotImplementedException;
use Jitesoft\Exceptions\Security\OAuth2\OAuth2Exception;
use Laravel\Lumen\Routing\Controller;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
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

    /**
     * @throws NotImplementedException
     */
    public function postRefreshTransientToken() {
        throw new NotImplementedException();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws OAuth2Exception
     */
    public function postIssueAccessToken(Request $request) {
        $psrRequest = $this->factory->createRequest($request);

        try {
            $out = $this->authorizationServer->respondToAccessTokenRequest($psrRequest, new Response());
        } catch (OAuthServerException $ex) {
            throw new OAuth2Exception($ex->getMessage(), $ex->getCode(), $ex);
        }

        return new \Illuminate\Http\Response(
            $out->getBody(),
            $out->getStatusCode(),
            $out->getHeaders()
        );
    }

    /**
     * @param Request $request
     */
    public function deleteAccessToken(Request $request) {
        $request->get('token');
    }

}
