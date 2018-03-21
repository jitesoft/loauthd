<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  Client.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Jitesoft\Loauthd\Entities\Contracts\ClientInterface;
use Jitesoft\Loauthd\Entities\Traits\IdentifierTrait;
use Jitesoft\Loauthd\Entities\Traits\IdTrait;
use Jitesoft\Loauthd\Grants\GrantHelper;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;

/**
 * Class Client
 *
 * @ORM\Entity
 * @ORM\Table(name="oauth2_clients")
 */
class Client implements ClientInterface {
    use IdentifierTrait;
    use IdTrait;

    /**
     * @var string
     * @ORM\Column(type="string", name="name", nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="text", name="redirect_url", nullable=false)
     */
    protected $redirectUrl;

    /**
     * @var Collection|AccessTokenEntityInterface[]|array
     * @ORM\OneToMany(
     *     targetEntity="AccessToken",
     *     mappedBy="client",
     *     orphanRemoval=true
     * )
     */
    protected $accessTokens;

    /**
     * @var Collection|AuthCodeEntityInterface[]|array
     * @ORM\OneToMany(
     *     targetEntity="AuthCode",
     *     mappedBy="client",
     *     orphanRemoval=true
     * )
     */
    protected $authCodes;

    /**
     * @var string
     * @ORM\Column(type="string", name="secret", length=255, nullable=true)
     */
    protected $secret;

    /**
     * @var int
     * @ORM\Column(type="smallint", name="grants")
     */
    protected $grants;

    /**
     * @param string $name
     * @param string $redirectUrl
     * @param null|string $secret
     * @param int $grants
     */
    public function __construct(string $name,
                                string $redirectUrl,
                                ?string $secret = null,
                                $grants = 0) {

        $this->name        = $name;
        $this->redirectUrl = $redirectUrl;
        $this->secret      = $secret;

        $this->setGrants($grants);

        $this->accessTokens = new ArrayCollection();
        $this->authCodes    = new ArrayCollection();
    }

    public function firstParty(): bool {
        return $this->hasGrant(GrantHelper::GRANT_TYPE_PASSWORD);
    }

    /**
     * @param int $grants
     * @see GrantHelper::GRANT_TYPE_*
     */
    public function addGrants(int $grants) {
        $this->grants |= $grants;
    }

    /**
     * @param int $grants
     * @see GrantHelper::GRANT_TYPE_*
     */
    public function removeGrants(int $grants) {
        $this->grants &= ~$grants;
    }

    public function setGrants(int $grants) {
        $this->grants = $grants;
    }

    /**
     * Passes a bit-flag for grant types. Verify it against the GrantHelper::GRANT_TYPES array.
     *
     * @param int $grant
     * @return bool
     * @see GrantHelper::GRANT_TYPE_*
     */
    public function hasGrant(int $grant): bool {
        return (($this->grants & $grant) === $grant);
    }

    /**
     * @param string $secret
     * @return bool
     */
    public function validateSecret(string $secret): bool {
        return $this->secret === $secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret) {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRedirectUri() {
        return $this->redirectUrl;
    }

}
