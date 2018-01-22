<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AccessTokenRepositoryTest.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Tests\Repositories\Doctrine;

use Carbon\Carbon;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Jitesoft\Log\NullLogger;
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
        $this->repository        = new AccessTokenRepository($this->entityManagerMock, new NullLogger());
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
        $token = new AccessToken(new Client(), [], Carbon::now());
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
        $token = new AccessToken(new Client(), [], Carbon::now());
        $token->setIdentifier(1);

        $this->entityManagerMock->shouldReceive('getRepository')->once()->with(get_class($token))->andReturn(
            Mockery::mock(ObjectRepository::class)->shouldReceive('findOneBy')->once()->with([
                'identifier' => 1
            ])->andReturn($token)->getMock()
        );

        try {
            $this->repository->persistNewAccessToken($token);
        } catch (UniqueTokenIdentifierConstraintViolationException $ex) {
            // Should happen.
            $this->assertTrue(true);
        }

        $this->entityManagerMock->mockery_verify();
    }

    public function testRevokeAccessToken() {
        $token = new AccessToken(new Client(), [], Carbon::now());
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
        $token = new AccessToken(new Client(), [], Carbon::now());
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
            ])->andReturn(new AccessToken(new Client(), [], Carbon::now()))->getMock()
        );

        $this->assertFalse($this->repository->isAccessTokenRevoked('1'));

        $this->entityManagerMock->mockery_verify();
    }

}
