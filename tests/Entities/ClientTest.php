<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  Client.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Tests\Entities;

use Jitesoft\Loauthd\Entities\Client;
use Jitesoft\Loauthd\Grants\GrantHelper;
use Jitesoft\Loauthd\Tests\TestCase;

class ClientTest extends TestCase {

    public function testHasGrant() {

        $client = new Client('test', 'example.com', 'secret', GrantHelper::GRANT_TYPE_PASSWORD | GrantHelper::GRANT_TYPE_IMPLICIT);
        $this->assertTrue($client->hasGrant(GrantHelper::GRANT_TYPE_PASSWORD));

        $client->addGrants(GrantHelper::GRANT_TYPE_CLIENT_CREDENTIALS);
        $this->assertTrue($client->hasGrant(GrantHelper::GRANT_TYPE_CLIENT_CREDENTIALS));
        $this->assertTrue($client->hasGrant(GrantHelper::GRANT_TYPE_PASSWORD));
        $this->assertTrue($client->hasGrant(GrantHelper::GRANT_TYPE_IMPLICIT));
        $this->assertTrue($client->hasGrant(GrantHelper::GRANT_TYPE_IMPLICIT & GrantHelper::GRANT_TYPE_PASSWORD));
        $this->assertTrue($client->hasGrant(GrantHelper::GRANT_TYPE_IMPLICIT | GrantHelper::GRANT_TYPE_PASSWORD));

        $client->removeGrants(GrantHelper::GRANT_TYPE_IMPLICIT);
        $this->assertFalse($client->hasGrant(GrantHelper::GRANT_TYPE_IMPLICIT));
        $this->assertTrue($client->hasGrant(GrantHelper::GRANT_TYPE_PASSWORD));
        $this->assertFalse($client->hasGrant(GrantHelper::GRANT_TYPE_IMPLICIT | GrantHelper::GRANT_TYPE_PASSWORD));
        $this->assertTrue($client->hasGrant(GrantHelper::GRANT_TYPE_IMPLICIT & GrantHelper::GRANT_TYPE_PASSWORD));
        $this->assertFalse($client->hasGrant(GrantHelper::GRANT_TYPE_IMPLICIT & ~GrantHelper::GRANT_TYPE_PASSWORD));
        $this->assertTrue($client->hasGrant(~GrantHelper::GRANT_TYPE_IMPLICIT & GrantHelper::GRANT_TYPE_PASSWORD));
    }

}
