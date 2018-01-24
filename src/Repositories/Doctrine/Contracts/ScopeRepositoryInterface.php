<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ScopeRepositoryInterface.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Repositories\Doctrine\Contracts;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface as LeagueRepositoryInterface;

interface ScopeRepositoryInterface extends LeagueRepositoryInterface {

    /**
     * Returns all scopes.
     *
     * @return array|ScopeEntityInterface[]
     */
    public function getAll(): array;

}
