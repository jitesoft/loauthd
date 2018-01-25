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
use Jitesoft\Loauthd\Entities\Contracts\ScopeInterface;
use Jitesoft\Loauthd\Entities\Traits\IdentifierTrait;
use Jitesoft\Loauthd\Entities\Traits\IdTrait;
use Jitesoft\Loauthd\Entities\Traits\TokenTrait;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * Class AuthCode
 *
 * @ORM\Entity
 * @ORM\Table(name="oauth2_auth_codes")
 */
class AuthCode implements AuthCodeInterface {
    use IdTrait;
    use TokenTrait;
    use IdentifierTrait;

    /**
     * @var Collection|array|TokenScope[]
     * @ORM\OneToMany(targetEntity="TokenScope", mappedBy="authCode", fetch="EAGER")
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
            $this->scopes->add(new TokenScope($scope, $this));
        }
    }

}
