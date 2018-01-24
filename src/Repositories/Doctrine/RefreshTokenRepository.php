<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  RefreshTokenRepository.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Repositories\Doctrine;

use Jitesoft\Exceptions\Database\Entity\UniqueConstraintException;
use Jitesoft\OAuth\Lumen\Entities\RefreshToken;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface as Token;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository extends AbstractRepository implements RefreshTokenRepositoryInterface {

    /**
     * Creates a new refresh token
     *
     * @return Token
     */
    public function getNewRefreshToken(): Token {
        return new RefreshToken();
    }

    /**
     * Create a new refresh token_name.
     *
     * @param Token $refreshTokenEntity
     *
     * @throws UniqueConstraintException
     */
    public function persistNewRefreshToken(Token $refreshTokenEntity) {
        $has = $this->em->getRepository(RefreshToken::class)->findOneBy([
            'identifier' => $refreshTokenEntity->getIdentifier()
        ]) !== null;

        if ($has) {
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
