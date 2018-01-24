<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ScopeRepositoryTest.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Tests\Repositories\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;
use Illuminate\Hashing\BcryptHasher;
use Jitesoft\Exceptions\Database\Entity\EntityException;
use Jitesoft\Exceptions\Security\OAuth2\InvalidGrantException;
use Jitesoft\Loauthd\Entities\Client;
use Jitesoft\Loauthd\Entities\Scope;
use Jitesoft\Loauthd\Entities\User;
use Jitesoft\Loauthd\OAuth;
use Jitesoft\Log\StdLogger;
use Jitesoft\Loauthd\Repositories\Doctrine\ScopeRepository;
use Jitesoft\Loauthd\Repositories\Doctrine\UserRepository;
use Jitesoft\Loauthd\Tests\TestCase;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Mockery;

class ScopeRepositoryTest extends TestCase {

    /** @var ScopeRepositoryInterface */
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
        $scope       = new Scope('scope_name');
        $expectation = $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(Scope::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => 'scope_name'])
                    ->andReturn($scope)
                    ->getMock()
            );

        $out = $this->repository->getScopeEntityByIdentifier('scope_name');
        $expectation->verify();
        $this->assertSame($out, $scope);
    }

    public function testGetScopeEntityByIdentifierDoesNotExist() {

        $expectation = $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(Scope::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => 'scope_name'])
                    ->andReturn(null)
                    ->getMock()
            );

        $out = $this->repository->getScopeEntityByIdentifier('scope_name');
        $expectation->verify();
        $this->assertNull($out);
    }

    public function testFinalizeScopesInvalidGrant() {
        $client = new Client('test', '', '', 0);
        $this->expectException(InvalidGrantException::class);
        $this->expectExceptionMessage('Invalid grant.');

        $this->repository->finalizeScopes([new Scope('whatevs')], 'password', $client);
    }

    public function testFinalizeScopesInvalidGrantNoneExistant() {
        $client = new Client('test', '', '', 0);
        $this->expectException(InvalidGrantException::class);
        $this->expectExceptionMessage('Invalid grant.');

        $this->repository->finalizeScopes([new Scope('whatevs')], 'aa', $client);
    }

    public function testFinalizeScopesInvalidUser() {
        $client = new Client('test', '', '', OAuth::GRANT_TYPE_PASSWORD);
        $expectation = $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(User::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => 'id'])
                    ->andReturn(null)
                    ->getMock()
            );

        try {
            $this->repository->finalizeScopes([new Scope('whatevs')], 'password', $client, 'id');
        } catch(EntityException $ex) {
            $this->assertEquals('Entity not found.', $ex->getMessage());
            $this->assertEquals(User::class, $ex->getEntityName());
            $expectation->verify();
            return;
        }

        $this->assertTrue(false);
    }

    public function testFinalizeScopesRemoveScopes() {

    }

    public function testFinalizeScopesAddScopes() {}

    public function testFinalizeScopes() {


    }

}
