<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  UserRepository.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Hashing\Hasher;
use Jitesoft\Exceptions\Security\InvalidCredentialsException;
use Jitesoft\Exceptions\Security\OAuth2\InvalidGrantException;
use Jitesoft\Loauthd\Entities\Contracts\ClientInterface;
use Jitesoft\Loauthd\Entities\Contracts\UserInterface;
use Jitesoft\Loauthd\Entities\User;
use Jitesoft\Loauthd\Grants\GrantHelper;
use Jitesoft\Loauthd\OAuthServiceProvider;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\UserRepositoryInterface;
use Psr\Log\LoggerInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface as Client;

class UserRepository extends AbstractRepository implements UserRepositoryInterface {

    /** @var Hasher */
    protected $hash;
    /** @var string */
    protected $userClass;
    /** @var string */
    protected $userKey;

    /**
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @param Hasher $hash
     */
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, Hasher $hash) {
        parent::__construct($em, $logger);

        $this->hash      = $hash;
        $this->userClass = OAuthServiceProvider::getConfig('user_model', User::class);
        $this->userKey   = OAuthServiceProvider::getConfig('user_identification', 'identifier');
    }

    /**
     * Get a user entity.
     *
     * @param string $username
     * @param string $password
     * @param string $grantType The grant type used
     * @param ClientInterface|Client $clientEntity
     * @return UserInterface|null
     * @throws InvalidCredentialsException
     * @throws InvalidGrantException
     */
    public function getUserEntityByUserCredentials($username,
                                                    $password,
                                                    $grantType,
                                                    Client $clientEntity): ?UserInterface {

        /** @var \Jitesoft\Loauthd\Entities\Client $clientEntity */
        if (!array_key_exists($grantType, GrantHelper::GRANT_TYPES)
            || !$clientEntity->hasGrant(GrantHelper::GRANT_TYPES[$grantType])) {
            throw new InvalidGrantException('Invalid grant.', $grantType);
        }

        /** @var UserInterface $user */
        $user = $this->em->getRepository($this->userClass)->findOneBy([
            $this->userKey  => $username
        ]);

        if (!$user) {
            $this->logger->error('Could not validate user. Invalid credentials.');
            throw new InvalidCredentialsException('Failed to validate user.');
        }

        return $this->hash->check($password, $user->getPassword()) ? $user : null;
    }

    /**
     * @param string $identifier
     * @return null|object|UserInterface
     */
    public function getUserByIdentifier(string $identifier): ?UserInterface {
        return $this->em->getRepository($this->userClass)->findOneBy([
            'identifier' => $identifier
        ]);
    }

}
