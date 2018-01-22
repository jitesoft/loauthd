<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  Client.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Entities;

use League\OAuth2\Server\Entities\ClientEntityInterface;

class Client implements ClientEntityInterface {

    /**
     * Get the client's identifier.
     *
     * @return string
     */
    public function getIdentifier() {
        // TODO: Implement getIdentifier() method.
    }

    /**
     * Get the client's name.
     *
     * @return string
     */
    public function getName() {
        // TODO: Implement getName() method.
    }

    /**
     * Returns the registered redirect URI (as a string).
     *
     * Alternatively return an indexed array of redirect URIs.
     *
     * @return string|string[]
     */
    public function getRedirectUri() {
        // TODO: Implement getRedirectUri() method.
    }
}
