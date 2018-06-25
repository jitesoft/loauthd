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
 * @ORM\Table(name="oauth2_scopes")
 */
class Scope implements ScopeInterface {
    use IdTrait;
    use IdentifierTrait;

    /**
     * @var string
     * @ORM\Column(type="string", name="scope_name", length=255)
     */
    public $scopeName;

    /**
     * @param string $identifier
     * @param string $scopeName
     */
    public function __construct(string $identifier, ?string $scopeName = null) {
        $this->identifier = $identifier;
        $this->scopeName  = $scopeName;
    }

    public function jsonSerialize() {
        return json_encode(['identifier' => $this->identifier, 'name' => $this->scopeName]);
    }

}
