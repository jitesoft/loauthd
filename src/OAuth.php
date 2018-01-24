<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OAuth.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace Jitesoft\Loauthd;

abstract class OAuth {

    public const CONFIG_NAMESPACE = 'loauthd';

    public const GRANT_TYPES = [
        'authorization_code' => self::GRANT_TYPE_AUTH_CODE,
        'refresh_token'      => self::GRANT_TYPE_REFRESH_TOKEN,
        'password'           => self::GRANT_TYPE_PASSWORD,
        'implicit'           => self::GRANT_TYPE_IMPLICIT,
        'client_credentials' => self::GRANT_TYPE_CLIENT_CREDENTIALS,
    ];

    public static function getGrantsAsFlags(...$grants) {
        $out = 0;

        foreach ($grants as $grant) {
            $out |= self::GRANT_TYPES[$grant];
        }
    }

    public const GRANT_TYPE_AUTH_CODE          = 1;
    public const GRANT_TYPE_REFRESH_TOKEN      = 2;
    public const GRANT_TYPE_PASSWORD           = 4;
    public const GRANT_TYPE_IMPLICIT           = 8;
    public const GRANT_TYPE_CLIENT_CREDENTIALS = 16;

}
