<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ScopeValidator.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd;

use Jitesoft\Loauthd\Contracts\ScopeValidatorInterface;
use Jitesoft\Loauthd\Entities\Contracts\UserInterface;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\ScopeRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

class ScopeValidator implements ScopeValidatorInterface {

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
                                   ScopeRepositoryInterface $scopeRepository): array {

        return $scopeRepository->getAll();
    }
}
