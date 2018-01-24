<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ScopeValidatorInterface.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Contracts;

use Jitesoft\Loauthd\Entities\Contracts\UserInterface;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\ScopeRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

interface ScopeValidatorInterface {

    /**
     * Validates scopes for a given client and optional user.
     *
     * @param array|ScopeEntityInterface[] $scopes
     * @param string $grantType
     * @param ClientEntityInterface $client
     * @param UserInterface $user
     * @param ScopeRepositoryInterface $scopeRepository
     * @return array
     */
    public function validateScopes(array $scopes,
                                    string $grantType,
                                    ClientEntityInterface $client,
                                    ?UserInterface $user,
                                    ScopeRepositoryInterface $scopeRepository): array;

}
