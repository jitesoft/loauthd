<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ClientInterface.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Entities\Contracts;

use League\OAuth2\Server\Entities\ClientEntityInterface;

interface ClientInterface extends ClientEntityInterface {

    /**
     * If the client entity is a first party client or not.
     *
     * @return bool
     */
    public function firstParty(): bool;

    /**
     * @param int $grant
     * @return bool
     * @see OAuth::GRANT_TYPE_*
     */
    public function hasGrant(int $grant): bool;

    /**
     * @param string $secret
     * @return bool
     */
    public function validateSecret(string $secret): bool;

}
