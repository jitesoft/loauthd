<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  IdentifierTrait.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Entities\Traits;

trait IdentifierTrait {

    /**
     * @var string
     * @ORM\Column(type="string", length=255, name="identifier", nullable=false, unique=true)
     */
    protected $identifier;

    /**
     * Get the token's identifier.
     *
     * @return string
     */
    public function getIdentifier(): string {
        return $this->identifier;
    }

    /**
     * Set the token's identifier.
     *
     * @param string $identifier
     */
    public function setIdentifier($identifier) {
        $this->identifier = $identifier;
    }

}
