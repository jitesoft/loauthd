<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  Scope.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Entities;

use Doctrine\ORM\Mapping as ORM;
use Jitesoft\Loauthd\Entities\Traits\IdentifierTrait;
use Jitesoft\Loauthd\Entities\Traits\IdTrait;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * Class Scope
 *
 * @ORM\Entity
 * @ORM\Table(name="oauth2/scopes")
 */
class Scope implements ScopeEntityInterface {
    use IdTrait;
    use IdentifierTrait;

    /**
     * @param string $identifier
     */
    public function __construct(string $identifier) {
        $this->identifier = $identifier;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize() {
        return json_encode($this->identifier);
    }

}
