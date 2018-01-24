<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AuthCodeRepositoryTest.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Tests\Repositories\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;
use Jitesoft\Exceptions\Database\Entity\UniqueConstraintException;
use Jitesoft\Log\NullLogger;
use Jitesoft\Loauthd\Entities\AuthCode;
use Jitesoft\Loauthd\Repositories\Doctrine\AuthCodeRepository;
use Jitesoft\Loauthd\Tests\TestCase;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Mockery;

class AuthCodeRepositoryTest extends TestCase {

    /** @var AuthCodeRepositoryInterface */
    protected $repository;

    protected function setUp() {
        parent::setUp();

        $this->repository = new AuthCodeRepository($this->entityManagerMock, new NullLogger());
    }

    public function testGetNewAuthCode() {
        $this->assertInstanceOf(AuthCodeEntityInterface::class, $this->repository->getNewAuthCode());
    }

    public function testPersistNewAuthCode() {
        $code = new AuthCode();
        $code->setIdentifier('1');

        $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(AuthCode::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => '1'])
                    ->andReturn(null)->getMock()
            );

        $this->entityManagerMock->shouldReceive('persist')->once()->with($code);

        $this->repository->persistNewAuthCode($code);
        $this->entityManagerMock->mockery_verify();
        $this->assertTrue(true);
    }

    public function testPersistNewAuthCodeError() {
        $code = new AuthCode();
        $code->setIdentifier('1');

        $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(AuthCode::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => '1'])
                    ->andReturn($code)
                    ->getMock()
            );

        try {
            $this->repository->persistNewAuthCode($code);
        } catch (UniqueConstraintException $ex) {
            $this->assertTrue(true);
        }

        $this->entityManagerMock->mockery_verify();
        $this->assertTrue(true);
    }

    public function testRevokeAuthCode() {
        $code = new AuthCode();
        $code->setIdentifier('1');

        $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(AuthCode::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => '1'])
                    ->andReturn($code)->getMock()
            );
        $this->entityManagerMock
            ->shouldReceive('remove')
            ->once()
            ->with($code);

        $this->repository->revokeAuthCode('1');
        $this->entityManagerMock->mockery_verify();
        $this->assertTrue(true);
    }


    public function testRevokeAuthCodeNoExist() {
        $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(AuthCode::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => '1'])
                    ->andReturn(null)->getMock()
            );

        $this->repository->revokeAuthCode('1');
        $this->entityManagerMock->mockery_verify();
        $this->assertTrue(true);
    }

    public function testIsAuthCodeRevoked() {

        $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(AuthCode::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => '1'])
                    ->andReturn(null)->getMock()
            );
        $this->assertTrue($this->repository->isAuthCodeRevoked(1));

        $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(AuthCode::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => '2'])
                    ->andReturn('something')->getMock()
            );
        $this->assertFalse($this->repository->isAuthCodeRevoked('2'));

        $this->entityManagerMock->mockery_verify();
        $this->assertTrue(true);
    }

}
