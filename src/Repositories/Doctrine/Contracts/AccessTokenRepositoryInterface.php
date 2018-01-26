<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AccessTokenRepositoryInterface.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Repositories\Doctrine\Contracts;

use Jitesoft\Loauthd\Entities\Contracts\AccessTokenInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface as LeagueRepositoryInterface;

/**
 * Interface AccessTokenRepositoryInterface
 *
 * Contract for AccessToken repositories.
 */
interface AccessTokenRepositoryInterface extends LeagueRepositoryInterface {

    /**
     * Find a single access token by its identifier.
     *
     * @param string $identifier
     * @return AccessTokenInterface|null
     */
    public function findByIdentifier(string $identifier): ?AccessTokenInterface;

}
