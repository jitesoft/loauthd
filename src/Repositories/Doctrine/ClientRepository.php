<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ClientRepository.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Repositories\Doctrine;

use Jitesoft\Exceptions\Security\InvalidCredentialsException;
use Jitesoft\Exceptions\Security\OAuth2\InvalidGrantException;
use Jitesoft\Loauthd\Entities\Client;
use Jitesoft\Loauthd\Entities\Contracts\ClientInterface;
use Jitesoft\Loauthd\Grants\GrantHelper;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\ClientRepositoryInterface;

class ClientRepository extends AbstractRepository implements ClientRepositoryInterface {

    /**
     * Get a client.
     *
     * @param string $identifier The client's identifier
     * @param string $grantType The grant type used
     * @param null|string $secret The client's secret (if sent)
     * @param bool $mustValidateSecret If true the client must attempt to validate the secret if the client
     *                                        is confidential
     * @return ClientInterface|null
     * @throws InvalidCredentialsException
     * @throws InvalidGrantException
     */
    public function getClientEntity($identifier,
                                    $grantType,
                                    $secret = null,
                                    $mustValidateSecret = true): ?ClientInterface {

        /** @var Client|null $client */
        $client = $this->em->getRepository(Client::class)->findOneBy([
            'identifier' => $identifier
        ]);

        if ($client === null) {
            $this->logger->warning(
                'Tried to fetch client with identifier {identifier}. Client did not exist.',
                [ 'identifier' => $identifier ]
            );
            return $client;
        }

        $grantFlag = 0;
        if ($grantType === '*') {
            foreach (array_values(GrantHelper::GRANT_TYPES) as $v) {
                $grantFlag |= $v;
            }
        } else {
            $grantFlag |= GrantHelper::GRANT_TYPES[$grantType];
        }

        if (!$client->hasGrant($grantFlag)) {
            $this->logger->warning(
                'Requested client (id: {id}) did not have the requested grant.',
                [ 'id' => $client->getId() ]);
            throw new InvalidGrantException('Client did not have requested grant.', $grantType);
        }

        if ($mustValidateSecret) {
            if (!$client->validateSecret($secret)) {
                $this->logger->error(
                    'Failed to authenticate client with id {id}. Secret was not correct.',
                    [ 'id' => $client->getId() ]
                );
                throw new InvalidCredentialsException('Could not validate Client secret.');
            }
        }

        return $client;
    }

}
