<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OAuthGuard.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Jitesoft\Exceptions\Database\Entity\EntityException;
use Jitesoft\Exceptions\Security\InvalidCredentialsException;
use Jitesoft\Exceptions\Security\OAuth2\OAuth2Exception;
use Jitesoft\Loauthd\Entities\Contracts\UserInterface;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\AccessTokenRepositoryInterface;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\ClientRepositoryInterface;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\UserRepositoryInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

class OAuthGuard implements Guard {
    use GuardHelpers;

    protected $userProvider;
    protected $resourceServer;
    protected $factory;
    protected $userRepository;
    protected $accessTokenRepository;
    protected $clientRepository;
    protected $request;

    public function __construct(ResourceServer $resourceServer,
                                 UserProvider $userProvider,
                                 UserRepositoryInterface $userRepository,
                                 ClientRepositoryInterface $clientRepository,
                                 AccessTokenRepositoryInterface $accessTokenRepository,
                                 Request $request) {

        $this->clientRepository      = $clientRepository;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->resourceServer        = $resourceServer;
        $this->factory               = new DiactorosFactory();
        $this->userProvider          = $userProvider;
        $this->userRepository        = $userRepository;
        $this->request               = $request;
    }

    /**
     * @return Authenticatable|null
     * @throws EntityException
     * @throws OAuth2Exception
     */
    public function user(): ?Authenticatable {
        if ($this->request->bearerToken() !== null) {
            $psrRequest = $this->factory->createRequest($this->request);
            return $this->authWithToken($psrRequest);
        }

        return null;
    }

    /**
     * @param ServerRequestInterface $request
     * @throws EntityException
     * @throws OAuth2Exception
     */
    protected function authWithToken(ServerRequestInterface $request) {
        try {
            $data = $this->resourceServer->validateAuthenticatedRequest($request);
        } catch (OAuthServerException $ex) {
            throw new OAuth2Exception($ex->getMessage(), $ex->getCode(), $ex);
        }

        $tokenId          = $data->getAttribute('oauth_access_token_id');
        $userId           = $data->getAttribute('oauth_user_id');
        $clientIdentifier = $data->getAttribute('oauth_client_id');
        $scopes           = $data->getAttribute('oauth_scopes');

        if ($this->accessTokenRepository->isAccessTokenRevoked($tokenId)) {
            throw new OAuth2Exception('Invalid. Access token is not active.');
        }

        /** @var UserInterface $user */
        $user = $this->userRepository->getUserByIdentifier($userId);
        if (!($user instanceof Authenticatable)) {
            throw new EntityException('Failed to set user. The entity does not inherit Authenticatable interface.');
        }

        $token = $this->accessTokenRepository->findByIdentifier($tokenId);
        $user->setAccessToken($token);
        $this->setUser($user);
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     * @throws InvalidCredentialsException
     */
    public function validate(array $credentials = []) {
        $user = $this->userProvider->retrieveByCredentials($credentials);
        if (!$user) {
            throw new InvalidCredentialsException('Invalid credentials.');
        }

        return $this->userProvider->validateCredentials($user, $credentials);
    }

}
