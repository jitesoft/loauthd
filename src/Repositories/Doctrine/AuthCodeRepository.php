<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AuthCodeRepository.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Repositories\Doctrine;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface as AuthCode;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository implements AuthCodeRepositoryInterface {

    /**
     * Creates a new AuthCode
     *
     * @return AuthCode
     */
    public function getNewAuthCode(): AuthCode {
        // TODO: Implement getNewAuthCode() method.
    }

    /**
     * Persists a new auth code to permanent storage.
     *
     * @param AuthCode $authCodeEntity
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAuthCode(AuthCode $authCodeEntity) {
        // TODO: Implement persistNewAuthCode() method.
    }

    /**
     * Revoke an auth code.
     *
     * @param string $codeId
     */
    public function revokeAuthCode($codeId) {
        // TODO: Implement revokeAuthCode() method.
    }

    /**
     * Check if the auth code has been revoked.
     *
     * @param string $codeId
     *
     * @return bool Return true if this code has been revoked
     */
    public function isAuthCodeRevoked($codeId) {
        // TODO: Implement isAuthCodeRevoked() method.
    }
}
