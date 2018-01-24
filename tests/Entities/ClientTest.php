<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  Client.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Tests\Entities;

use Jitesoft\Loauthd\Entities\Client;
use Jitesoft\Loauthd\OAuth;
use Jitesoft\Loauthd\Tests\TestCase;

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
