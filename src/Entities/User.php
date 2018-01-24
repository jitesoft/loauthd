<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  User.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Entities;

use Doctrine\ORM\Mapping as ORM;
use Jitesoft\OAuth\Lumen\Entities\Contracts\UserInterface;
use Jitesoft\OAuth\Lumen\Entities\Traits\IdTrait;
use Jitesoft\OAuth\Lumen\Entities\Traits\OAuthUserEntityTrait;

/**
 * Class User
 *
 * @ORM\Entity
 * @ORM\Table(name="oauth2/users")
 */
class User implements UserInterface {
    use OAuthUserEntityTrait;
    use IdTrait;

    /**
     * @param string $identifier
     * @param string $authKey
     * @param string $password
     */
    public function __construct(string $identifier, string $authKey, string $password) {
        $this->identifier = $identifier;
        $this->authKey    = $authKey;
        $this->password   = $password;
    }

}
