<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  Client.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Jitesoft\OAuth\Lumen\Entities\Traits\IdentifierTrait;
use Jitesoft\OAuth\Lumen\Entities\Traits\IdTrait;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * Class Client
 *
 * @ORM\Entity
 * @ORM\Table(name="oauth2/clients")
 */
class Client implements ClientEntityInterface {
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

    public function __construct(string $name, string $redirectUrl) {
        $this->name        = $name;
        $this->redirectUrl = $redirectUrl;

        $this->accessTokens = new ArrayCollection();
        $this->authCodes    = new ArrayCollection();
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
