<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AccessTokenRepositoryTest.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Tests\Repositories\Doctrine;

use Carbon\Carbon;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Jitesoft\Exceptions\Database\Entity\UniqueConstraintException;
use Jitesoft\Log\NullLogger;
use Jitesoft\Loauthd\Entities\AccessToken;
use Jitesoft\Loauthd\Entities\Client;
use Jitesoft\Loauthd\Repositories\Doctrine\AccessTokenRepository;
use Jitesoft\Loauthd\Tests\TestCase;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use Mockery;
use Mockery\MockInterface;

class AccessTokenRepositoryTest extends TestCase {

    /** @var MockInterface|EntityManagerInterface */
    protected $entityManagerMock;
    /** @var AccessTokenRepository */
    protected $repository;

    protected function setUp() {
        parent::setUp();
        $this->entityManagerMock = Mockery::mock(EntityManagerInterface::class);
        $this->repository        = new AccessTokenRepository($this->entityManagerMock, new NullLogger());
    }

    public function testGetNewToken() {
        $this->assertInstanceOf(
            AccessTokenEntityInterface::class,
            $this->repository->getNewToken(
                new Client('test', '?https://example.com'),
                [],
                1
            )
        );
    }

    public function testPersistNewAccessToken() {
        $token = new AccessToken(new Client('test', '?https://example.com'), [], Carbon::now());
        $token->setIdentifier(1);

        $this->entityManagerMock->shouldReceive('getRepository')->once()->with(get_class($token))->andReturn(
            Mockery::mock(ObjectRepository::class)->shouldReceive('findOneBy')->once()->with([
                'identifier' => 1
            ])->andReturn(null)->getMock()
        );

        $this->entityManagerMock->shouldReceive('persist')->once()->with(
            $token
        );
        $this->repository->persistNewAccessToken($token);

        $this->entityManagerMock->mockery_verify();
        $this->assertTrue(true);
    }

    public function testPersistNewAccessTokenExists() {
        $token = new AccessToken(new Client('test', '?https://example.com'), [], Carbon::now());
        $token->setIdentifier(1);

        $this->entityManagerMock->shouldReceive('getRepository')->once()->with(get_class($token))->andReturn(
            Mockery::mock(ObjectRepository::class)->shouldReceive('findOneBy')->once()->with([
                'identifier' => 1
            ])->andReturn($token)->getMock()
        );

        try {
            $this->repository->persistNewAccessToken($token);
        } catch (UniqueConstraintException $ex) {
            // Should happen.
            $this->assertTrue(true);
        }

        $this->entityManagerMock->mockery_verify();
    }

    public function testRevokeAccessToken() {
        $token = new AccessToken(new Client('test', '?https://example.com'), [], Carbon::now());
        $token->setIdentifier(1);

        $this->entityManagerMock->shouldReceive('getRepository')->once()->with(get_class($token))->andReturn(
            Mockery::mock(ObjectRepository::class)->shouldReceive('findOneBy')->once()->with([
                'identifier' => 1
            ])->andReturn($token)->getMock()
        );

        $this->entityManagerMock->shouldReceive('remove')->once()->with($token);
        $this->repository->revokeAccessToken($token->getIdentifier());

        $this->entityManagerMock->mockery_verify();
        $this->assertTrue(true);
    }

    public function testIsAccessTokenRevoked() {
        $token = new AccessToken(new Client('test', '?https://example.com'), [], Carbon::now());
        $token->setIdentifier(1);

        $this->entityManagerMock->shouldReceive('getRepository')->once()->with(get_class($token))->andReturn(
            Mockery::mock(ObjectRepository::class)->shouldReceive('findOneBy')->once()->with([
                'identifier' => 1
            ])->andReturn(null)->getMock()
        );

        $this->assertTrue($this->repository->isAccessTokenRevoked('1'));

        $this->entityManagerMock->shouldReceive('getRepository')->once()->with(get_class($token))->andReturn(
            Mockery::mock(ObjectRepository::class)->shouldReceive('findOneBy')->once()->with([
                'identifier' => 1
            ])->andReturn(new AccessToken(new Client('test', '?https://example.com'), [], Carbon::now()))->getMock()
        );

        $this->assertFalse($this->repository->isAccessTokenRevoked('1'));

        $this->entityManagerMock->mockery_verify();
    }

}
