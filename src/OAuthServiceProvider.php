<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OAuthServiceProvider.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;

/**
 * Class OAuthServiceProvider
 */
class OAuthServiceProvider extends ServiceProvider {

    public function register() {
        /** @var Application $app */
        $app = $this->app;


        if (config('oauth2', null) === null) {
            $app->configure('oauth2');
        }
    }

    public function boot() {

    }
}
