<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ClientRepositoryTest.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Tests\Repositories\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;
use Jitesoft\Exceptions\Security\InvalidCredentialsException;
use Jitesoft\Exceptions\Security\OAuth2\InvalidGrantException;
use Jitesoft\Log\StdLogger;
use Jitesoft\Loauthd\Entities\Client;
use Jitesoft\Loauthd\OAuth;
use Jitesoft\Loauthd\Repositories\Doctrine\ClientRepository;
use Jitesoft\Loauthd\Tests\TestCase;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Mockery;

class ClientRepositoryTest extends TestCase {

    /** @var ClientRepositoryInterface */
    protected $repository;

    protected function setUp() {
        parent::setUp();

        $this->repository = new ClientRepository($this->entityManagerMock, new StdLogger());
    }

    public function testGetClientEntity() {
        $client = new Client('test', 'example.com', null, OAuth::GRANT_TYPE_PASSWORD);
        $client->setIdentifier('test');

        $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(Client::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => 'test'])
                    ->andReturn($client)
                    ->getMock()
            );


        $entity = $this->repository->getClientEntity('test', 'password', null, false);
        $this->entityManagerMock->mockery_verify();
        $this->assertInstanceOf(ClientEntityInterface::class, $entity);
        $this->assertSame($client, $entity);
    }

    public function testGetClientEntityNull() {

        $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(Client::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => 'test'])
                    ->andReturn(null)
                    ->getMock()
            );

        $out = $this->repository->getClientEntity('test', 'password');
        $this->assertNull($out);
        $this->entityManagerMock->mockery_verify();
    }

    public function testGetClientEntitySecretValidation() {
        $client = new Client('test', 'example.com', 'secret', OAuth::GRANT_TYPE_PASSWORD);

        $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(Client::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => 'test'])
                    ->andReturn($client)
                    ->getMock()
            );

        $entity = $this->repository->getClientEntity('test', 'password', 'secret', true);
        $this->entityManagerMock->mockery_verify();
        $this->assertInstanceOf(ClientEntityInterface::class, $entity);
        $this->assertSame($client, $entity);
    }

    public function testGetClientEntitySecretValidationFailure() {
        $client = new Client('test', 'example.com', '!secret', OAuth::GRANT_TYPE_PASSWORD);

        $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(Client::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => 'test'])
                    ->andReturn($client)
                    ->getMock()
            );

        try {
            $this->repository->getClientEntity('test', 'password', 'secret', true);
        } catch (InvalidCredentialsException $ex) {
            $this->assertEquals('Could not validate Client secret.', $ex->getMessage());
            return;
        }
        $this->assertTrue(false); // Should not go here.
    }

    public function testGetClientEntityInvalidGrantType() {
        $client = new Client('test', 'example.com'); // No grant passed, so 0.

        $this->entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(Client::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => 'test'])
                    ->andReturn($client)
                    ->getMock()
            );

        try {
            $this->repository->getClientEntity('test', 'password', null, false);
        } catch (InvalidGrantException $ex) {
            $this->assertEquals('Client did not have requested grant.', $ex->getMessage());
            return;
        }
        $this->assertTrue(false); // Should not go here.
    }

}
