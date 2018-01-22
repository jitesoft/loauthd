<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AccessToken.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace Jitesoft\OAuth\Lumen\Entities;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_access_tokens")
 */
class AccessToken implements AccessTokenEntityInterface {
    use AccessTokenTrait;

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="identifier")
     */
    protected $identifier;

    /**
     * @var Carbon
     * @ORM\Column(type="datetime", name="expiry", nullable=false)
     */
    protected $expiry;

    /**
     * @var string
     * @ORM\Column(type="string", name="user_identifier", nullable=true)
     */
    protected $userIdentifier;

    /**
     * @var ClientEntityInterface
    // MAP!
     */
    protected $client;

    /**
     * @var array|ScopeEntityInterface
     * // MAP!
     */
    protected $scopes;

    /**
     * AccessToken constructor.
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

    /**
     * @return ClientEntityInterface
     */
    public function getClient(): ClientEntityInterface {
        return $this->client;
    }

    /**
     * @return \DateTime|Carbon
     */
    public function getExpiryDateTime(): Carbon {
        return $this->expiry;
    }

    /**
     * @return string|int
     */
    public function getUserIdentifier(): ?string {
        return $this->userIdentifier;
    }

    /**
     * @return ScopeEntityInterface[]|array
     */
    public function getScopes(): array {
        return $this->scopes->toArray();
    }

    /**
     * Set the date time when the token expires.
     *
     * @param \DateTime $dateTime
     */
    public function setExpiryDateTime(\DateTime $dateTime) {
        $this->expiry = Carbon::instance($dateTime);
    }

    /**
     * Set the identifier of the user associated with the token.
     *
     * @param string|int $identifier The identifier of the user
     */
    public function setUserIdentifier($identifier) {
        $this->userIdentifier = $identifier;
    }

    /**
     * Set the client that the token was issued to.
     *
     * @param ClientEntityInterface $client
     */
    public function setClient(ClientEntityInterface $client) {
        $this->client = $client;
    }

    /**
     * Associate a scope with the token.
     *
     * @param ScopeEntityInterface $scope
     */
    public function addScope(ScopeEntityInterface $scope) {
        $this->scopes->add($scope);
    }

    /**
     * Get the token's identifier.
     *
     * @return string
     */
    public function getIdentifier(): string {
        return (string)$this->identifier;
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
