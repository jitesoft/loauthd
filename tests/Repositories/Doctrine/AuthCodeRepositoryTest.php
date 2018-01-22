<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AuthCodeRepositoryTest.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Tests\Repositories\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Jitesoft\Log\NullLogger;
use Jitesoft\OAuth\Lumen\Entities\AuthCode;
use Jitesoft\OAuth\Lumen\Repositories\Doctrine\AuthCodeRepository;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

class AuthCodeRepositoryTest extends TestCase {

    /** @var AuthCodeRepositoryInterface */
    protected $repository;

    /** @var EntityManagerInterface|Mockery\Mock */
    protected $entityManagerMock;

    protected function setUp() {
        parent::setUp();

        $this->entityManagerMock = Mockery::mock(EntityManagerInterface::class);
        $this->repository        = new AuthCodeRepository($this->entityManagerMock, new NullLogger());
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
        } catch (UniqueTokenIdentifierConstraintViolationException $ex) {
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
