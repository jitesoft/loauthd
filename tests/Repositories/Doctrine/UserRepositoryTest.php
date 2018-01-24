<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  UserRepositoryTest.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Tests\Repositories\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;
use Illuminate\Hashing\BcryptHasher;
use Jitesoft\Exceptions\Security\OAuth2\InvalidGrantException;
use Jitesoft\Log\StdLogger;
use Jitesoft\OAuth\Lumen\Entities\Client;
use Jitesoft\OAuth\Lumen\Entities\User;
use Jitesoft\OAuth\Lumen\OAuth;
use Jitesoft\OAuth\Lumen\Repositories\Doctrine\Contracts\UserRepositoryInterface;
use Jitesoft\OAuth\Lumen\Repositories\Doctrine\UserRepository;
use Jitesoft\OAuth\Lumen\Tests\TestCase;
use Mockery;
use phpmock\Mock;
use phpmock\MockBuilder;

class UserRepositoryTest extends TestCase {

    /** @var UserRepositoryInterface */
    protected $repository;

    protected function setUp() {
        parent::setUp();

        $this->repository = new UserRepository($this->entityManagerMock, new StdLogger(), new BcryptHasher());
    }

    public function testGetUserEntityByUserCredentials() {
        $client = new Client('test', '', null, OAuth::GRANT_TYPE_PASSWORD);
        $user   = new User('abc', 'test', (new BcryptHasher())->make('abc'));

        $this->entityManagerMock->shouldReceive('getRepository')
            ->once()
            ->with(User::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['username' => 'test'])
                    ->andReturn($user)
                    ->getMock()
            );

        $builder = new MockBuilder();
        $mock    = $builder
            ->setNamespace((new \ReflectionClass(UserRepository::class))->getNamespaceName())
            ->setName('config')
            ->setFunction(function(string $config, string $default) {
                $this->assertEquals('oauth2.user_identification',  $config);
                $this->assertEquals('authKey', $default);
                return 'username';
            }
            )->build();
        $mock->enable();

        $out = $this->repository
            ->getUserEntityByUserCredentials(
                $user->getAuthKey(),
                'abc',
                'password',
                $client
            );

        $this->assertSame($user, $out);
    }

    public function testGetUserEntityByUserCredentialsInvalidGrant() {
        $client = new Client('test', '', null, OAuth::GRANT_TYPE_PASSWORD);

        try {
            $this->repository
                ->getUserEntityByUserCredentials(
                    'abc',
                    '123',
                    'another_grant',
                    $client
                );
        } catch (InvalidGrantException $ex) {

            $this->assertEquals('Invalid grant.', $ex->getMessage());
            $this->assertEquals('another_grant', $ex->getGrant());
            return;
        }

        $this->assertTrue(false);
    }

    public function testGetUserByIdentifier() {
        $user   = new User('abc', 'test', (new BcryptHasher())->make('abc'));

        $this->entityManagerMock->shouldReceive('getRepository')
            ->once()
            ->with(User::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => 'abc'])
                    ->andReturn($user)
                    ->getMock()
            );

        $out = $this->repository->getUserByIdentifier('abc');
        $this->assertSame($out, $user);
    }

    public function testGetUserByIdentifierNone() {
        $this->entityManagerMock->shouldReceive('getRepository')
            ->once()
            ->with(User::class)
            ->andReturn(
                Mockery::mock(ObjectRepository::class)
                    ->shouldReceive('findOneBy')
                    ->once()
                    ->with(['identifier' => 'abc'])
                    ->andReturn(null)
                    ->getMock()
            );

        $out = $this->repository->getUserByIdentifier('abc');
        $this->assertNull($out);
    }

}
