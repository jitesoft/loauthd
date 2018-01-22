<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  IdentifierTrait.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Entities\Traits;

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
     * @param string|int $identifier
     */
    public function setIdentifier($identifier) {
        if (is_string($identifier)) {
            $identifier = intval($identifier);
        }

        $this->identifier = $identifier;
    }

}
