<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ClientRepository.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Repositories\Doctrine;

use Jitesoft\Exceptions\Security\InvalidCredentialsException;
use Jitesoft\Exceptions\Security\OAuth2\InvalidGrantException;
use Jitesoft\Loauthd\Entities\Client;
use Jitesoft\Loauthd\OAuth;
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
     * @throws InvalidCredentialsException
     * @throws InvalidGrantException
     */
    public function getClientEntity($identifier, $grantType, $secret = null, $mustValidateSecret = true): ?Client {
        /** @var Client|null $client */
        $client = $this->em->getRepository(Client::class)->findOneBy([
            'identifier' => $identifier
        ]);

        if ($client === null) {
            return $client;
        }

        $grantFlag = 0;
        if ($grantType === '*') {
            foreach (array_values(OAuth::GRANT_TYPES) as $v) {
                $grantFlag |= $v;
            }
        } else {
            $grantFlag |= OAuth::GRANT_TYPES[$grantType];
        }

        if (!$client->hasGrant($grantFlag)) {
            throw new InvalidGrantException('Client did not have requested grant.', $grantType);
        }

        if ($mustValidateSecret) {
            if (!$client->validateSecret($secret)) {
                throw new InvalidCredentialsException('Could not validate Client secret.');
            }
        }

        return $client;
    }

}
