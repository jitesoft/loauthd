<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  UserRepository.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Repositories\Doctrine;

use League\OAuth2\Server\Entities\ClientEntityInterface as Client;
use League\OAuth2\Server\Entities\UserEntityInterface as User;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface {

    /**
     * Get a user entity.
     *
     * @param string $username
     * @param string $password
     * @param string $grantType The grant type used
     * @param Client $clientEntity
     *
     * @return User
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, Client $clientEntity): User {
        // TODO: Implement getUserEntityByUserCredentials() method.
    }
}
