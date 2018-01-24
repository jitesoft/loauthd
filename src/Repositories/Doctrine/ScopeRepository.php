<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ScopeRepository.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Repositories\Doctrine;

use League\OAuth2\Server\{
    Entities\ClientEntityInterface as Client,
    Entities\ScopeEntityInterface as Scope,
    Repositories\ScopeRepositoryInterface
};

class ScopeRepository extends AbstractRepository implements ScopeRepositoryInterface {

    /**
     * Return information about a scope.
     *
     * @param string $identifier The scope identifier
     *
     * @return Scope
     */
    public function getScopeEntityByIdentifier($identifier): Scope {
        // TODO: Implement getScopeEntityByIdentifier() method.
    }

    /**
     * Given a client, grant type and optional user identifier validate
     * the set of scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @param Scope[] $scopes
     * @param string $grantType
     * @param Client $clientEntity
     * @param null|string $userIdentifier
     *
     * @return Scope[]|array
     */
    public function finalizeScopes(array $scopes, $grantType, Client $clientEntity, $userIdentifier = null): array {


        $outScopes = [];


    }

}
