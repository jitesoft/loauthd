<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AccessTokenRepository.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Repositories\Doctrine;

use Carbon\Carbon;
use Jitesoft\Exceptions\Database\Entity\UniqueConstraintException;
use Jitesoft\Loauthd\Entities\AccessToken;
use Jitesoft\Loauthd\Entities\Contracts\AccessTokenInterface;
use Jitesoft\Loauthd\Entities\Contracts\ClientInterface;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface as Token;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

class AccessTokenRepository extends AbstractRepository implements AccessTokenRepositoryInterface {

    /**
     * Create a new access token
     *
     * @param ClientInterface|ClientEntityInterface $clientEntity
     * @param ScopeEntityInterface[] $scopes
     * @param string|null $userIdentifier
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
     * @throws UniqueConstraintException
     */
    public function persistNewAccessToken(Token $accessTokenEntity) {
        $out = $this->em->getRepository(AccessToken::class)->findOneBy([
           'identifier' => intval($accessTokenEntity->getIdentifier())
        ]);

        if ($out !== null) {
            $this->logger->warning('Access token with id {id} already exist.', ['id' => $out->getId()]);
            throw new UniqueConstraintException('AccessToken already exist.', AccessToken::class);
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
            'identifier' => $tokenId
        ]);

        if (!$out) {
            $this->logger->warning('Tried to revoke an access token which did not exist.');
            return;
        }

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
            'identifier' => $tokenId
        ]);

        return $out === null;
    }

    /**
     * Find a single access token by its identifier.
     *
     * @param string $identifier
     * @return AccessTokenInterface|null|object
     */
    public function findByIdentifier(string $identifier): ?AccessTokenInterface {
        return $this->em->getRepository(AccessToken::class)->findOneBy([
            'identifier' => $identifier
        ]);
    }
}
