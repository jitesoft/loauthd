<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  TokenScope.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Entities;

use Doctrine\ORM\Mapping as ORM;
use Jitesoft\Loauthd\Entities\Traits\IdTrait;

/**
 * Class TokenScope
 *
 * @ORM\Entity
 * @ORM\Table(name="oauth2_token_scopes")
 */
class TokenScope {
    use IdTrait;

    /**
     * @var null|AuthCode
     * @ORM\ManyToOne(targetEntity="AuthCode", inversedBy="scopes")
     */
    protected $authCode;

    /**
     * @var null|AccessToken
     * @ORM\ManyToOne(targetEntity="AccessToken", inversedBy="scopes")
     */
    protected $accessToken;

    /**
     * @var Scope
     * @ORM\ManyToOne(targetEntity="Scope")
     */
    protected $scope;

    /**
     * TokenScope constructor.
     * @param Scope $scope
     * @param AuthCode|null $authCode
     * @param AccessToken|null $accessToken
     */
    public function __construct(Scope $scope, ?AuthCode $authCode, ?AccessToken $accessToken = null) {
        $this->authCode    = $authCode;
        $this->accessToken = $accessToken;
        $this->scope       = $scope;
    }

    /**
     * @return Scope
     */
    public function getScope(): Scope {
        return $this->scope;
    }

}
