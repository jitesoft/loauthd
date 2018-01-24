<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AuthCodeRepository.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Repositories\Doctrine;

use Jitesoft\Exceptions\Database\Entity\UniqueConstraintException;
use Jitesoft\Loauthd\Entities\AuthCode;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface as AuthCodeInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository extends AbstractRepository implements AuthCodeRepositoryInterface {

    /**
     * Creates a new AuthCode
     *
     * @return AuthCodeInterface
     */
    public function getNewAuthCode(): AuthCodeInterface {
        return new AuthCode();
    }

    /**
     * Persists a new auth code to permanent storage.
     *
     * @param AuthCodeInterface $authCodeEntity
     *
     * @throws UniqueConstraintException
     */
    public function persistNewAuthCode(AuthCodeInterface $authCodeEntity) {
        $out = $this->em->getRepository(AuthCode::class)->findOneBy([
            'identifier' => $authCodeEntity->getIdentifier()
        ]);

        if ($out !== null) {
            throw new UniqueConstraintException('AuthCode already exist.', AuthCode::class);
        }

        $this->em->persist($authCodeEntity);
    }

    /**
     * Revoke an auth code.
     *
     * @param string $codeId
     */
    public function revokeAuthCode($codeId) {
        $out = $this->em->getRepository(AuthCode::class)->findOneBy([
            'identifier' => $codeId
        ]);

        if ($out === null) {
            return;
        }

        $this->em->remove($out);
    }

    /**
     * Check if the auth code has been revoked.
     *
     * @param string $codeId
     *
     * @return bool Return true if this code has been revoked
     */
    public function isAuthCodeRevoked($codeId) {
        $out = $this->em->getRepository(AuthCode::class)->findOneBy([
            'identifier' => $codeId
        ]);

        return $out == null;
    }
}
