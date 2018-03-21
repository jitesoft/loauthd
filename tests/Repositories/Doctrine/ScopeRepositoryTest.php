<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ScopeRepositoryTest.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Tests\Repositories\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;
use Illuminate\Hashing\BcryptHasher;
use Jitesoft\Exceptions\Database\Entity\EntityException;
use Jitesoft\Exceptions\Security\OAuth2\InvalidGrantException;
use Jitesoft\Loauthd\Contracts\ScopeValidatorInterface;
use Jitesoft\Loauthd\Entities\Client;
use Jitesoft\Loauthd\Entities\Scope;
use Jitesoft\Loauthd\Entities\User;
use Jitesoft\Loauthd\Grants\GrantHelper;
use Jitesoft\Loauthd\OAuthServiceProvider;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\ScopeRepositoryInterface;
use Jitesoft\Loauthd\ScopeValidator;
use Jitesoft\Log\NullLogger;
use Jitesoft\Log\StdLogger;
use Jitesoft\Loauthd\Repositories\Doctrine\ScopeRepository;
use Jitesoft\Loauthd\Repositories\Doctrine\UserRepository;
use Jitesoft\Loauthd\Tests\TestCase;
use Mockery;
use phpmock\MockBuilder;
use ReflectionClass;

class ScopeRepositoryTest extends TestCase {

    /** @var ScopeRepositoryInterface */
    protected $repository;

    protected function setUp() {
        parent::setUp();

        $logger  = new NullLogger();
        $builder = new MockBuilder();
        $mock    = $builder
            ->setNamespace((new ReflectionClass(OAuthServiceProvider::class))->getNamespaceName())
            ->setName('config')
            ->setFunction(function(string $key, $default = null) {
                return $default;
            }
            )->build();

        $mock->enable();
        $this->repository = new ScopeRepository(
            $this->entityManagerMock,
            $logger,
            new UserRepository($this->entityManagerMock, $logger, new BcryptHasher()),
            new ScopeValidator()
        );
        $mock->disable();
    }


    public function testGetAll() {

        $expectation = $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->twice()
            ->with(Scope::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findAll')
                    ->twice()
                    ->andReturn([], [new Scope('a'), new Scope('b'), new Scope('c')])
                    ->getMock()
            );

        $this->assertEmpty($this->repository->getAll());
        $this->assertCount(3, $this->repository->getAll());
        $expectation->verify();
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
        $client      = new Client('test', '', '', GrantHelper::GRANT_TYPE_PASSWORD);
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
        $ret    = [new Scope('test'), new Scope('test2')];
        $req    = [new Scope('test'), new Scope('test2'), new Scope('test3')];
        $client = new Client('test', '', '', GrantHelper::GRANT_TYPE_PASSWORD);

        $mock = Mockery::mock(ScopeValidatorInterface::class);
        $exp  = $mock->shouldReceive('validateScopes')
            ->once()
            ->with($req, 'password', $client, null, $this->repository)
            ->andReturn($ret);

        $ref  = new \ReflectionClass(ScopeRepository::class);
        $prop = $ref->getProperty('scopeValidator');
        $prop->setAccessible(true);
        $prop->setValue($this->repository, $mock);

        $out = $this->repository->finalizeScopes($req, 'password', $client, null);
        $this->assertEquals($out, $ret);
        $exp->verify();
    }

    public function testFinalizeScopesAddScopes() {
        $ret    = [new Scope('test'), new Scope('test2')];
        $req    = [new Scope('test2')];
        $client = new Client('test', '', '', GrantHelper::GRANT_TYPE_PASSWORD);

        $mock = Mockery::mock(ScopeValidatorInterface::class);
        $exp  = $mock->shouldReceive('validateScopes')
            ->once()
            ->with($req, 'password', $client, null, $this->repository)
            ->andReturn($ret);

        $ref  = new \ReflectionClass(ScopeRepository::class);
        $prop = $ref->getProperty('scopeValidator');
        $prop->setAccessible(true);
        $prop->setValue($this->repository, $mock);

        $out = $this->repository->finalizeScopes($req, 'password', $client, null);
        $this->assertEquals($out, $ret);
        $exp->verify();
    }

}
