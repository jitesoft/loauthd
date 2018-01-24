<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AuthCode.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Jitesoft\Loauthd\Entities\Traits\IdentifierTrait;
use Jitesoft\Loauthd\Entities\Traits\TokenTrait;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * Class AuthCode
 *
 * @ORM\Entity
 * @ORM\Table(name="oauth2/auth_codes")
 */
class AuthCode implements AuthCodeEntityInterface {
    use TokenTrait;
    use IdentifierTrait;

    /**
     * @var Collection|array|Scope[]
     */
    protected $scopes;


    /**
     * @var ClientEntityInterface
     * @ORM\OneToMany(
     *     targetEntity="Client",
     *     mappedBy="authCodes"
     * )
     */
    protected $client;

    /**
     * @var null|string
     * @ORM\Column(type="text", name="redirect_uri", nullable=true)
     */
    protected $redirectUri;

    public function __construct() {
        $this->scopes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getRedirectUri() {
        return $this->redirectUri;
    }

    /**
     * @param string $uri
     */
    public function setRedirectUri($uri) {
        $this->redirectUri = $uri;
    }

}
