<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  Client.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Tests\Entities;

use Jitesoft\OAuth\Lumen\Entities\Client;
use Jitesoft\OAuth\Lumen\OAuth;
use Jitesoft\OAuth\Lumen\Tests\TestCase;

class ClientTest extends TestCase {

    public function testHasGrant() {

        $client = new Client('test', 'example.com', 'secret', OAuth::GRANT_TYPE_PASSWORD | OAuth::GRANT_TYPE_IMPLICIT);
        $this->assertTrue($client->hasGrant(OAuth::GRANT_TYPE_PASSWORD));

        $client->addGrants(OAuth::GRANT_TYPE_CLIENT_CREDENTIALS);
        $this->assertTrue($client->hasGrant(Oauth::GRANT_TYPE_CLIENT_CREDENTIALS));
        $this->assertTrue($client->hasGrant(OAuth::GRANT_TYPE_PASSWORD));
        $this->assertTrue($client->hasGrant(OAuth::GRANT_TYPE_IMPLICIT));
        $this->assertTrue($client->hasGrant(OAuth::GRANT_TYPE_IMPLICIT & OAuth::GRANT_TYPE_PASSWORD));
        $this->assertTrue($client->hasGrant(OAuth::GRANT_TYPE_IMPLICIT | OAuth::GRANT_TYPE_PASSWORD));

        $client->removeGrants(OAuth::GRANT_TYPE_IMPLICIT);
        $this->assertFalse($client->hasGrant(OAuth::GRANT_TYPE_IMPLICIT));
        $this->assertTrue($client->hasGrant(OAuth::GRANT_TYPE_PASSWORD));
        $this->assertFalse($client->hasGrant(OAuth::GRANT_TYPE_IMPLICIT | OAuth::GRANT_TYPE_PASSWORD));
        $this->assertTrue($client->hasGrant(OAuth::GRANT_TYPE_IMPLICIT & OAuth::GRANT_TYPE_PASSWORD));
        $this->assertFalse($client->hasGrant(OAuth::GRANT_TYPE_IMPLICIT & ~OAuth::GRANT_TYPE_PASSWORD));
        $this->assertTrue($client->hasGrant(~OAuth::GRANT_TYPE_IMPLICIT & OAuth::GRANT_TYPE_PASSWORD));
    }

}
