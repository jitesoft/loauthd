<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AccessToken.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Entities;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Jitesoft\OAuth\Lumen\Entities\Traits\IdentifierTrait;
use Jitesoft\OAuth\Lumen\Entities\Traits\IdTrait;
use Jitesoft\OAuth\Lumen\Entities\Traits\TokenTrait;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth2/access_tokens")
 */
class AccessToken implements AccessTokenEntityInterface {
    use AccessTokenTrait;
    use TokenTrait;
    use IdentifierTrait;
    use IdTrait;

    /**
     * @var RefreshTokenEntityInterface[]|Collection
     * @ORM\OneToMany(
     *     targetEntity="RefreshToken",
     *     mappedBy="accessToken",
     *     orphanRemoval=true
     * )
     */
    protected $refreshTokens;

    /**
     * @var ClientEntityInterface
     * @ORM\OneToMany(
     *     targetEntity="Client",
     *     mappedBy="accessTokens"
     * )
     */
    protected $client;

    /**
     * @var ArrayCollection|Scope[]|array
     */
    protected $scopes;

    /**
     * @param ClientEntityInterface $client
     * @param array $scopes
     * @param null $userIdentifier
     * @param Carbon $expireTime
     */
    public function __construct(ClientEntityInterface $client,
                                array $scopes,
                                Carbon $expireTime,
                                $userIdentifier = null) {

        $this->client         = $client;
        $this->userIdentifier = $userIdentifier;
        $this->expiry         = $expireTime;

        $this->scopes = new ArrayCollection($scopes);
    }

}
