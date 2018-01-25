<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  Scope.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Entities;

use Doctrine\ORM\Mapping as ORM;
use Jitesoft\Loauthd\Entities\Contracts\ScopeInterface;
use Jitesoft\Loauthd\Entities\Traits\IdentifierTrait;
use Jitesoft\Loauthd\Entities\Traits\IdTrait;

/**
 * Class Scope
 *
 * @ORM\Entity
 * @ORM\Table(name="oauth2/scopes")
 */
class Scope implements ScopeInterface {
    use IdTrait;
    use IdentifierTrait;

    /**
     * @param string $identifier
     */
    public function __construct(string $identifier) {
        $this->identifier = $identifier;
    }

    public function jsonSerialize() {
        return json_encode($this->identifier);
    }

}
