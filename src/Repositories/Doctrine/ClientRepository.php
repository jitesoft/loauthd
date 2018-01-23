<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ClientRepository.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Repositories\Doctrine;

use Jitesoft\Exceptions\Database\Entity\EntityException;
use Jitesoft\OAuth\Lumen\Entities\Client;
use Jitesoft\OAuth\Lumen\OAuth;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository extends AbstractRepository implements ClientRepositoryInterface {

    /**
     * Get a client.
     *
     * @param string $identifier The client's identifier
     * @param string $grantType The grant type used
     * @param null|string $secret The client's secret (if sent)
     * @param bool $mustValidateSecret If true the client must attempt to validate the secret if the client
     *                                        is confidential
     * @return Client|null
     * @throws EntityException
     */
    public function getClientEntity($identifier, $grantType, $secret = null, $mustValidateSecret = true): ?Client {
        /** @var Client|null $client */
        $client = $this->em->getRepository(Client::class)->findOneBy([
            'identifier' => $identifier
        ]);

        if ($client === null) {
            return $client;
        }

        if ($grantType === '*') {
            $grantType |= array_map(function($i) {
                return $i;
            }, array_values(OAuth::GRANT_TYPES));
        }

        if (!$client->hasGrant(OAuth::GRANT_TYPES[$grantType])) {
            throw new EntityException('Client did not have requested grant.');
        }

        if ($mustValidateSecret) {
            if (!$client->validateSecret($secret)) {
                throw new EntityException('Could not validate Client secret.');
            }
        }

        return $client;
    }

}
