<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AccessTokenRepositoryTest.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Tests\Repositories\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Jitesoft\OAuth\Lumen\Entities\AccessToken;
use Jitesoft\OAuth\Lumen\Entities\Client;
use Jitesoft\OAuth\Lumen\Repositories\Doctrine\AccessTokenRepository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class AccessTokenRepositoryTest extends TestCase {

    /** @var MockInterface|EntityManagerInterface */
    protected $entityManagerMock;
    /** @var AccessTokenRepository */
    protected $repository;

    protected function setUp() {
        parent::setUp();
        $this->entityManagerMock = Mockery::mock(EntityManagerInterface::class);
        $this->repository        = new AccessTokenRepository($this->entityManagerMock);
    }

    public function testGetNewToken() {
        $this->assertInstanceOf(
            AccessTokenEntityInterface::class,
            $this->repository->getNewToken(
                new Client(),
                [],
                1
            )
        );
    }

    public function testPersistNewAccessToken() {
        $token = new AccessToken();
        $token->setIdentifier('abc');

        $this->entityManagerMock->shouldReceive('getRepository')->once()->with(get_class($token))->andReturn(
            Mockery::mock(ObjectRepository::class)->shouldReceive('findBy')->once()->with([
                'identifier' => 'abc'
            ])->andReturn(null)
        );
        $this->entityManagerMock->shouldReceive('persist')->once()->with(
            $token
        );
        $this->repository->persistNewAccessToken($token);
        $this->assertTrue(true);
    }

    public function testPersistNewAccessTokenExists() {
        $token = new AccessToken();
        $token->setIdentifier('abc');

        $this->entityManagerMock->shouldReceive('getRepository')->once()->with(get_class($token))->andReturn(
            Mockery::mock(ObjectRepository::class)->shouldReceive('findBy')->once()->with([
                'identifier' => 'abc'
            ])->andReturn($token)
        );

        $this->expectException(UniqueTokenIdentifierConstraintViolationException::class);
        $this->repository->persistNewAccessToken(new AccessToken());
        $this->assertTrue(true);
    }

    public function testRevokeAccessToken() {
        $token = new AccessToken();
        $token->setIdentifier('abc');

        $this->entityManagerMock->shouldReceive('remove')->once()->with('abc');
        $this->repository->revokeAccessToken($token->getIdentifier());

        $this->assertTrue(true);
    }

    public function testIsAccessTokenRevoked() {
        $this->entityManagerMock->shouldReceive('getRepository')->once()->with(get_class($token))->andReturn(
            Mockery::mock(ObjectRepository::class)->shouldReceive('findBy')->once()->with([
                'identifier' => 'abc'
            ])->andReturn(null)
        );

        $this->assertTrue($this->repository->isAccessTokenRevoked('abc'));

        $this->entityManagerMock->shouldReceive('getRepository')->once()->with(get_class($token))->andReturn(
            Mockery::mock(ObjectRepository::class)->shouldReceive('findBy')->once()->with([
                'identifier' => 'abc'
            ])->andReturn(new AccessToken())
        );

        $this->assertFalse($this->repository->isAccessTokenRevoked('abc'));
    }

}
