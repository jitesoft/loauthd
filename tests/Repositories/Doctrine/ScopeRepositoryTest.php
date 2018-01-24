<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ScopeRepositoryTest.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Tests\Repositories\Doctrine;

use Illuminate\Hashing\BcryptHasher;
use Jitesoft\Log\StdLogger;
use Jitesoft\OAuth\Lumen\Repositories\Doctrine\ScopeRepository;
use Jitesoft\OAuth\Lumen\Repositories\Doctrine\UserRepository;
use Jitesoft\OAuth\Lumen\Tests\TestCase;

class ScopeRepositoryTest extends TestCase {

    protected $repository;

    protected function setUp() {
        parent::setUp();

        $logger           = new StdLogger();
        $this->repository = new ScopeRepository(
            $this->entityManagerMock,
            $logger,
            new UserRepository($this->entityManagerMock, $logger, new BcryptHasher())
        );
    }

    public function testGetScopeEntityByIdentifier() {





    }

    public function testFinalizeScopes() {}

    public function testFinalizeScopesInvalidGrant() {}

    public function testFinalizeScopesInvalidUser() {}

    public function testFinalizeScopesRemoveScopes() {}

    public function testFinalizeScopesAddScopes() {}

}
