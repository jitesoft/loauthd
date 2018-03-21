<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  HasTokenTrait.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Entities\Traits;

use Jitesoft\Loauthd\Entities\Contracts\AccessTokenInterface;

/**
 * Trait HasTokenTrait
 *
 * Trait for easy implementation of access token methods of the UserInterface contract.
 */
trait HasTokenTrait {

    /**
     * AccessToken entity, should not be persisted.
     *
     * @var AccessTokenInterface|null
     */
    protected $accessToken;

    /**
     * @param AccessTokenInterface $accessToken
     */
    public function setAccessToken(AccessTokenInterface $accessToken) {
        $this->accessToken = $accessToken;
    }

    /**
     * @return AccessTokenInterface|null
     */
    public function getAccessToken(): ?AccessTokenInterface {
        return $this->accessToken;
    }

}
