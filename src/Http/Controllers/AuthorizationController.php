<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AuthorizationController.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace Jitesoft\Loauthd\Http\Controllers;

use Jitesoft\Exceptions\Security\OAuth2\OAuth2Exception;
use Laravel\Lumen\Routing\Controller;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequestFactory;

class AuthorizationController extends Controller {

    protected $authorizationServer;

    public function __construct(AuthorizationServer $authorizationServer) {
        $this->authorizationServer = $authorizationServer;
    }

    public function postAccessToken() {
        $request = ServerRequestFactory::fromGlobals();

        try {

            $response = $this->authorizationServer->respondToAccessTokenRequest($request, new JsonResponse([]));

            return new \Illuminate\Http\JsonResponse(
                $response->getBody(),
                $response->getStatusCode(),
                $response->getHeaders()
            );

        } catch (OAuthServerException $exception) {
            throw new OAuth2Exception($exception->getMessage(), $exception->getHttpStatusCode(), $exception);
        }
    }



}
