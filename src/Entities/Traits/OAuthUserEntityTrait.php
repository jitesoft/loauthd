<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OAuthUserEntityTrait.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Entities\Traits;

trait OAuthUserEntityTrait {
    use IdentifierTrait;

    /**
     * @var string
     * @ORM\Column(type="string", name="auth_key", length=255, unique=true)
     */
    protected $authKey;

    /**
     * @var string
     * @ORM\Column(type="string", name="user_name", length=255, unique=true)
     */
    protected $password;

    /**
     * Return the user's identifier.
     *
     * @return mixed
     */
    public function getIdentifier() {
        return $this->identifier;
    }

    /**
     * Get the authentication key of the user.
     * The key is the value that will be used to log a user in,
     * for example a username or an email address.
     *
     * @return string
     */
    public function getAuthKey(): string {
        return $this->authKey;
    }

    /**
     * Get the hashed password that the user have stored.
     * The password will be checked with the `oauth2.password_hash`.
     *
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

}
