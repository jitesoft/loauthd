<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  UserInterface.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Entities\Contracts;

use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * Interface UserInterface
 *
 * Contract for users.
 */
interface UserInterface extends UserEntityInterface {

    /**
     * Get the authentication key of the user.
     * The key is the value that will be used to log a user in,
     * for example a username or an email address.
     *
     * @return string
     */
    public function getAuthKey(): string;

    /**
     * Get the hashed password that the user have stored.
     * The password will be checked with the `oauth2.password_hash`.
     *
     * @return string
     */
    public function getPassword(): string;

}
