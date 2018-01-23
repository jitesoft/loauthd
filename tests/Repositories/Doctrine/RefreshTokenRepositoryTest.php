<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  RefreshTokenRepositoryTest.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Tests\Repositories\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;
use Jitesoft\Log\StdLogger;
use Jitesoft\OAuth\Lumen\Entities\RefreshToken;
use Jitesoft\OAuth\Lumen\Repositories\Doctrine\RefreshTokenRepository;
use Jitesoft\OAuth\Lumen\Tests\TestCase;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Mockery;

class RefreshTokenRepositoryTest extends TestCase {

    /** @var RefreshTokenRepositoryInterface */
    protected $repository;

    protected function setUp() {
        parent::setUp();

        $this->repository = new RefreshTokenRepository($this->entityManagerMock, new StdLogger());
    }

    public function testGetNewRefreshToken() {
        $token = $this->repository->getNewRefreshToken();
        $this->assertInstanceOf(RefreshTokenEntityInterface::class, $token);
    }

    public function testPersistNewRefreshToken() {
        $token = new RefreshToken();
        $token->setIdentifier('abc');

        $expectation = $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(RefreshToken::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => 'abc'])
                    ->andReturn(null)
                    ->getMock()
            );

        $expectation2 = $this->entityManagerMock->shouldReceive('persist')
            ->once()
            ->with($token);

        $this->repository->persistNewRefreshToken($token);
        $expectation->verify();
        $expectation2->verify();
        $this->assertTrue(true);
    }

    public function testPersistNewRefreshTokenFailure() {
        $token = new RefreshToken();
        $token->setIdentifier('abc');

        $expectation = $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(RefreshToken::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => 'abc'])
                    ->andReturn($token)
                    ->getMock()
            );

        try {
            $this->repository->persistNewRefreshToken($token);
        } catch (UniqueTokenIdentifierConstraintViolationException $ex) {
            $expectation->verify();
            $this->assertTrue(true);
            return;
        }

        $this->assertTrue(false);

    }

    public function testRevokeRefreshToken() {
        $token = new RefreshToken();
        $token->setIdentifier('abc');

        $expectation = $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(RefreshToken::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => 'abc'])
                    ->andReturn($token)
                    ->getMock()
            );

        $expectation2 = $this->entityManagerMock
            ->shouldReceive('remove')
            ->once()
            ->with($token);

        $this->repository->revokeRefreshToken($token->getIdentifier());
        $expectation->verify();
        $expectation2->verify();
        $this->assertTrue(true);
    }

    public function testIsRefreshTokenRevoked() {
        $token = new RefreshToken();
        $token->setIdentifier('abc');

        $expectation = $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->twice()
            ->with(RefreshToken::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => 'abc'])
                    ->andReturn(null)
                    ->getMock(),
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => '123'])
                    ->andReturn($token)
                    ->getMock()
            );

        $this->assertTrue($this->repository->isRefreshTokenRevoked($token->getIdentifier()));
        $this->assertFalse($this->repository->isRefreshTokenRevoked('123'));
        $expectation->verify();

    }

}
