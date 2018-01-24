<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ScopeRepositoryTest.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Tests\Repositories\Doctrine;

use Jitesoft\Log\StdLogger;
use Jitesoft\OAuth\Lumen\Repositories\Doctrine\ScopeRepository;
use Jitesoft\OAuth\Lumen\Tests\TestCase;

class ScopeRepositoryTest extends TestCase {

    protected $repository;

    protected function setUp() {
        parent::setUp();

        $this->repository = new ScopeRepository($this->entityManagerMock, new StdLogger());
    }

    public function testGetScopeEntityByIdentifier() {



    }

    public function testFinalizeScopes() {}

}
