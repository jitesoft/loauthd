<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AccessToken.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace Jitesoft\OAuth\Lumen\Entities;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

class AccessToken implements AccessTokenEntityInterface {
    use AccessTokenTrait;
    use EntityTrait;
    use TokenEntityTrait;

}
