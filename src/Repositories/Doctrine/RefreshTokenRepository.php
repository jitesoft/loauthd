<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  RefreshTokenRepository.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Repositories\Doctrine;

use Jitesoft\Exceptions\Database\Entity\UniqueConstraintException;
use Jitesoft\Loauthd\Entities\Contracts\RefreshTokenInterface;
use Jitesoft\Loauthd\Entities\RefreshToken;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface as Token;

class RefreshTokenRepository extends AbstractRepository implements RefreshTokenRepositoryInterface {

    /**
     * Creates a new refresh token
     *
     * @return RefreshTokenInterface
     */
    public function getNewRefreshToken(): RefreshTokenInterface {
        return new RefreshToken();
    }

    /**
     * Create a new refresh token_name.
     *
     * @param RefreshTokenInterface|Token $refreshTokenEntity
     *
     * @throws UniqueConstraintException
     */
    public function persistNewRefreshToken(Token $refreshTokenEntity) {
        $has = $this->em->getRepository(RefreshToken::class)->findOneBy([
            'identifier' => $refreshTokenEntity->getIdentifier()
        ]) !== null;

        if ($has) {
            $this->logger->warning('Failed to persist Refresh token, token already exists.');
            throw new UniqueConstraintException('RefreshToken already exist.', RefreshToken::class);
        }

        $this->em->persist($refreshTokenEntity);
    }

    /**
     * Revoke the refresh token.
     *
     * @param string $tokenId
     */
    public function revokeRefreshToken($tokenId) {
        $entity = $this->em->getRepository(RefreshToken::class)->findOneBy([
            'identifier' => $tokenId
        ]);
        if ($entity !== null) {
            $this->em->remove($entity);
        }
        $this->logger->warning('Failed to revoke refresh token. Token did not exist.');
    }

    /**
     * Check if the refresh token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isRefreshTokenRevoked($tokenId) {
        return $this->em->getRepository(RefreshToken::class)->findOneBy([
            'identifier' => $tokenId
        ]) === null;
    }

}
