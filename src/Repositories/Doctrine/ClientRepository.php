<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ClientRepository.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Repositories\Doctrine;

use League\OAuth2\Server\Entities\ClientEntityInterface as Client;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface {

    /**
     * Get a client.
     *
     * @param string $identifier The client's identifier
     * @param string $grantType The grant type used
     * @param null|string $secret The client's secret (if sent)
     * @param bool $mustValidateSecret If true the client must attempt to validate the secret if the client
     *                                        is confidential
     *
     * @return Client
     */
    public function getClientEntity($identifier, $grantType, $secret = null, $mustValidateSecret = true): Client {
        // TODO: Implement getClientEntity() method.
    }
}
