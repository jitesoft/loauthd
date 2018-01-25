<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AccessToken.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Entities;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Jitesoft\Loauthd\Entities\Contracts\AccessTokenInterface;
use Jitesoft\Loauthd\Entities\Contracts\ClientInterface;
use Jitesoft\Loauthd\Entities\Contracts\RefreshTokenInterface;
use Jitesoft\Loauthd\Entities\Contracts\ScopeInterface;
use Jitesoft\Loauthd\Entities\Traits\IdentifierTrait;
use Jitesoft\Loauthd\Entities\Traits\IdTrait;
use Jitesoft\Loauthd\Entities\Traits\TokenTrait;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth2_access_tokens")
 */
class AccessToken implements AccessTokenInterface {
    use AccessTokenTrait;
    use TokenTrait;
    use IdentifierTrait;
    use IdTrait;

    /**
     * @var RefreshTokenInterface[]|Collection
     * @ORM\OneToMany(
     *     targetEntity="RefreshToken",
     *     mappedBy="accessToken",
     *     orphanRemoval=true
     * )
     */
    protected $refreshTokens;

    /**
     * @var ClientInterface
     * @ORM\OneToMany(
     *     targetEntity="Client",
     *     mappedBy="accessTokens"
     * )
     */
    protected $client;

    /**
     * @var Collection|array|TokenScope[]
     * @ORM\OneToMany(targetEntity="TokenScope", mappedBy="accessToken", fetch="EAGER")
     */
    protected $scopes;

    /**
     * @param ClientInterface $client
     * @param array $scopes
     * @param string|null $userIdentifier
     * @param Carbon $expireTime
     */
    public function __construct(ClientInterface $client,
                                array $scopes,
                                Carbon $expireTime,
                                ?string $userIdentifier = null) {

        $this->client         = $client;
        $this->userIdentifier = $userIdentifier;
        $this->expiry         = $expireTime;

        $this->scopes = new ArrayCollection($scopes);
    }

    /**
     * Associate a scope with the token.
     *
     * @param ScopeEntityInterface|Scope|ScopeInterface $scope
     */
    public function addScope(ScopeEntityInterface $scope) {
        $has = $this->scopes->exists(function(TokenScope $s) use($scope) {
            return $s->getScope() === $scope;
        });
        if (!$has) {
            $this->scopes->add(new TokenScope($scope, null, $this));
        }
    }

}
