<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  RefreshToken.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Entities;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Jitesoft\Loauthd\Entities\Contracts\RefreshTokenInterface;
use Jitesoft\Loauthd\Entities\Traits\IdentifierTrait;
use Jitesoft\Loauthd\Entities\Traits\IdTrait;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

/**
 * Class RefreshToken
 *
 * @ORM\Entity
 * @ORM\Table(name="oauth2_refresh_tokens")
 */
class RefreshToken implements RefreshTokenInterface {
    use IdentifierTrait;
    use IdTrait;

    /**
     * @var AccessTokenEntityInterface
     * @ORM\ManyToOne(
     *     targetEntity="AccessToken",
     *     inversedBy="refreshTokens"
     * )
     */
    protected $accessToken;

    /**
     * @var Carbon
     * @ORM\Column(type="datetime", name="expiry", nullable=false)
     */
    protected $expiry;

    /**
     * @param AccessTokenEntityInterface $accessToken
     */
    public function setAccessToken(AccessTokenEntityInterface $accessToken) {
        $this->accessToken = $accessToken;
    }

    /**
     * @return AccessTokenEntityInterface
     */
    public function getAccessToken() {
        return $this->accessToken;
    }

    /**
     * @return \DateTime
     */
    public function getExpiryDateTime() {
        return $this->expiry;
    }

    /**
     * @param \DateTime $dateTime
     */
    public function setExpiryDateTime(\DateTime $dateTime) {
        $this->expiry = Carbon::instance($dateTime);
    }

}
