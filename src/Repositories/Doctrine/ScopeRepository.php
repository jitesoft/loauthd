<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ScopeRepository.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Jitesoft\Exceptions\Database\Entity\EntityException;
use Jitesoft\Exceptions\Security\OAuth2\InvalidGrantException;
use Jitesoft\Loauthd\Contracts\ScopeValidatorInterface;
use Jitesoft\Loauthd\Entities\Contracts\ClientInterface;
use Jitesoft\Loauthd\Entities\Scope;
use Jitesoft\Loauthd\Entities\User;
use Jitesoft\Loauthd\OAuth;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\UserRepositoryInterface;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\ScopeRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface as Client;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use Psr\Log\LoggerInterface;

class ScopeRepository extends AbstractRepository implements ScopeRepositoryInterface {

    /** @var UserRepositoryInterface */
    protected $userRepository;

    /** @var ScopeValidatorInterface */
    protected $scopeValidator;

    /**
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @param UserRepositoryInterface $userRepository
     * @param ScopeValidatorInterface $scopeValidator
     */
    public function __construct(EntityManagerInterface $em,
                                 LoggerInterface $logger,
                                 UserRepositoryInterface $userRepository,
                                 ScopeValidatorInterface $scopeValidator) {

        parent::__construct($em, $logger);

        $this->userRepository = $userRepository;
        $this->scopeValidator = $scopeValidator;
    }

    /**
     * Return information about a scope.
     *
     * @param string $identifier The scope identifier
     *
     * @return Scope|null|ScopeEntityInterface|object
     */
    public function getScopeEntityByIdentifier($identifier): ?ScopeEntityInterface {
        return $this->em->getRepository(Scope::class)->findOneBy([
            'identifier' => $identifier
        ]);
    }

    /**
     * Given a client, grant type and optional user identifier validate
     * the set of scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @param Scope[]|ScopeEntityInterface[]|array $scopes
     * @param string $grantType
     * @param Client|ClientInterface $clientEntity
     * @param null|string $userIdentifier
     * @return array
     * @throws EntityException
     * @throws InvalidGrantException
     */
    public function finalizeScopes(array $scopes, $grantType, Client $clientEntity, $userIdentifier = null): array {
        if (!array_key_exists($grantType, OAuth::GRANT_TYPES)
            || !$clientEntity->hasGrant(OAuth::GRANT_TYPES[$grantType])) {
            throw new InvalidGrantException('Invalid grant.', $grantType);
        }

        $user = null;
        if ($userIdentifier !== null) {
            $user = $this->userRepository->getUserByIdentifier($userIdentifier);

            if ($user === null) {
                throw new EntityException('Entity not found.', User::class);
            }
        }

        return $this->scopeValidator->validateScopes($scopes, $grantType, $clientEntity, $user, $this);
    }

    /**
     * Returns all scopes.
     *
     * @return array|ScopeEntityInterface[]
     */
    public function getAll(): array {
        return $this->em->getRepository(Scope::class)->findAll();
    }

}
