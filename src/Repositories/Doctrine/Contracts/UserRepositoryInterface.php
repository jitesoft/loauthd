<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  UserRepositoryInterface.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Repositories\Doctrine\Contracts;

use Jitesoft\Loauthd\Entities\Contracts\UserInterface;
use \League\OAuth2\Server\Repositories\UserRepositoryInterface as LeagueUserRepositoryInterface;

/**
 * Interface UserRepositoryInterface
 *
 * Contract for User repositories.
 */
interface UserRepositoryInterface extends LeagueUserRepositoryInterface {

    /**
     * @param string $identifier
     * @return UserInterface|null
     */
    public function getUserByIdentifier(string $identifier): ?UserInterface;

}
