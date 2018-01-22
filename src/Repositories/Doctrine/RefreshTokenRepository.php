<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  RefreshTokenRepository.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Repositories\Doctrine;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface as Token;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface {

    /**
     * Creates a new refresh token
     *
     * @return Token
     */
    public function getNewRefreshToken(): Token {
        // TODO: Implement getNewRefreshToken() method.
    }

    /**
     * Create a new refresh token_name.
     *
     * @param Token $refreshTokenEntity
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewRefreshToken(Token $refreshTokenEntity) {
        // TODO: Implement persistNewRefreshToken() method.
    }

    /**
     * Revoke the refresh token.
     *
     * @param string $tokenId
     */
    public function revokeRefreshToken($tokenId) {
        // TODO: Implement revokeRefreshToken() method.
    }

    /**
     * Check if the refresh token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isRefreshTokenRevoked($tokenId) {
        // TODO: Implement isRefreshTokenRevoked() method.
    }
}
