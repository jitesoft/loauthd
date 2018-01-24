<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ScopeRepository.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Jitesoft\Exceptions\Security\OAuth2\InvalidGrantException;
use Jitesoft\OAuth\Lumen\Entities\Contracts\ClientInterface;
use Jitesoft\OAuth\Lumen\OAuth;
use Jitesoft\OAuth\Lumen\Repositories\Doctrine\Contracts\UserRepositoryInterface;
use League\OAuth2\Server\{
    Entities\ClientEntityInterface as Client,
    Entities\ScopeEntityInterface as Scope,
    Repositories\ScopeRepositoryInterface
};
use Psr\Log\LoggerInterface;

class ScopeRepository extends AbstractRepository implements ScopeRepositoryInterface {

    protected $userRepository;

    public function __construct(EntityManagerInterface $em,
                                 LoggerInterface $logger,
                                 UserRepositoryInterface $userRepository) {

        parent::__construct($em, $logger);

        $this->userRepository = $userRepository;
    }

    /**
     * Return information about a scope.
     *
     * @param string $identifier The scope identifier
     *
     * @return Scope|null
     */
    public function getScopeEntityByIdentifier($identifier): Scope {
        $this->em->getRepository(Scope::class)->findOneBy([
            'identifier' => $identifier
        ]);
    }

    /**
     * Given a client, grant type and optional user identifier validate
     * the set of scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @param Scope[] $scopes
     * @param string $grantType
     * @param Client|ClientInterface $clientEntity
     * @param null|string $userIdentifier
     * @return array
     * @throws InvalidGrantException
     */
    public function finalizeScopes(array $scopes, $grantType, Client $clientEntity, $userIdentifier = null): array {
        if (!array_key_exists($grantType, OAuth::GRANT_TYPES)
            || !$clientEntity->hasGrant(OAuth::GRANT_TYPES[$grantType])) {
            throw new InvalidGrantException('Invalid grant.', $grantType);
        }

        $outScopes = [];

        $user = null;
        if ($userIdentifier) {
            $user = $this->userRepository->getUserByIdentifier($userIdentifier);
        }

    }

}
