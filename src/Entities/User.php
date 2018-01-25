<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  User.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Entities;

use Doctrine\ORM\Mapping as ORM;
use Jitesoft\Loauthd\Entities\Contracts\UserInterface;
use Jitesoft\Loauthd\Entities\Traits\IdTrait;
use Jitesoft\Loauthd\Entities\Traits\OAuthUserEntityTrait;

/**
 * Class User
 *
 * User entity which could be used either as a decorator or inherited.
 * If wanted, it could be swapped for another entity implementing the UserInterface contract.
 * @see UserInterface
 *
 * @ORM\Entity
 * @ORM\Table(name="oauth2_users")
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
