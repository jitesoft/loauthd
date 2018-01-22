<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AccessTokenRepository.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Repositories\Doctrine;

use Carbon\Carbon;
use Jitesoft\OAuth\Lumen\Entities\AccessToken;
use League\OAuth2\Server\{
    Entities\AccessTokenEntityInterface as Token,
    Entities\ClientEntityInterface,
    Entities\ScopeEntityInterface,
    Exception\UniqueTokenIdentifierConstraintViolationException,
    Repositories\AccessTokenRepositoryInterface
};

/**
 * Class AccessTokenRepository
 *
 * Doctrine implementation of the AccessTokenRepositoryInterface provided by League\OAuth2\Server.
 * Makes use of the EntityManager::getRepository(AccessToken::class) methods.
 */
class AccessTokenRepository extends AbstractRepository implements AccessTokenRepositoryInterface {

    /**
     * Create a new access token
     *
     * @param ClientEntityInterface $clientEntity
     * @param ScopeEntityInterface[] $scopes
     * @param mixed $userIdentifier
     *
     * @return Token
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null): Token {
        $this->logger->debug('Creating new access token.');
        return new AccessToken($clientEntity, $scopes, Carbon::now()->addHour(1), $userIdentifier);
    }

    /**
     * Persists a new access token to permanent storage.
     *
     * @param Token $accessTokenEntity
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAccessToken(Token $accessTokenEntity) {
        $out = $this->em->getRepository(AccessToken::class)->findOneBy([
           'identifier' => intval($accessTokenEntity->getIdentifier())
        ]);

        $this->logger->debug('Trying to persist new access token. Token does {not}exist.',
            [
                'not'  => $out === null ? 'not ' : ''
            ]
        );
        if ($out !== null) {
            throw new UniqueTokenIdentifierConstraintViolationException(
                'AccessToken already exist.', 1, 'Unique constraint failed.'
            );
        }

        $this->em->persist($accessTokenEntity);
    }

    /**
     * Revoke an access token.
     *
     * @param string $tokenId
     */
    public function revokeAccessToken($tokenId) {
        $out = $this->em->getRepository(AccessToken::class)->findOneBy([
            'identifier' => intval($tokenId)
        ]);

        if (!$out) {
            $this->logger->warning('Tried to revoke an access token which did not exist.');
            return;
        }

        $this->logger->debug('Revoking access token.');

        $this->em->remove($out);
    }

    /**
     * Check if the access token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isAccessTokenRevoked($tokenId): bool {
        $out = $this->em->getRepository(AccessToken::class)->findOneBy([
            'identifier' => intval($tokenId)
        ]);

        return $out === null;
    }
}
