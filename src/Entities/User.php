<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  User.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Entities;

use Doctrine\ORM\Mapping as ORM;
use Jitesoft\OAuth\Lumen\Entities\Traits\IdentifierTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * Class User
 *
 * @ORM\Entity
 * @ORM\Table(name="oauth2/users")
 */
class User implements UserEntityInterface {
    use IdentifierTrait;

    /**
     * @param string $identifier
     */
    public function __construct(string $identifier) {
        $this->identifier = $identifier;
    }

}
