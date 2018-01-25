<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AuthCode.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Jitesoft\Loauthd\Entities\Contracts\AuthCodeInterface;
use Jitesoft\Loauthd\Entities\Contracts\ClientInterface;
use Jitesoft\Loauthd\Entities\Traits\IdentifierTrait;
use Jitesoft\Loauthd\Entities\Traits\TokenTrait;

/**
 * Class AuthCode
 *
 * @ORM\Entity
 * @ORM\Table(name="oauth2_auth_codes")
 */
class AuthCode implements AuthCodeInterface {
    use TokenTrait;
    use IdentifierTrait;

    /**
     * @var Collection|array|Scope[]
     */
    protected $scopes;

    /**
     * @var ClientInterface
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
