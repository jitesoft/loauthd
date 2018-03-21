<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  UserRepositoryTest.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Tests\Repositories\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;
use Illuminate\Hashing\BcryptHasher;
use Jitesoft\Exceptions\Security\OAuth2\InvalidGrantException;
use Jitesoft\Loauthd\OAuthServiceProvider;
use Jitesoft\Log\StdLogger;
use Jitesoft\Loauthd\Entities\Client;
use Jitesoft\Loauthd\Entities\User;
use Jitesoft\Loauthd\Grants\GrantHelper;
use Jitesoft\Loauthd\Repositories\Doctrine\Contracts\UserRepositoryInterface;
use Jitesoft\Loauthd\Repositories\Doctrine\UserRepository;
use Jitesoft\Loauthd\Tests\TestCase;
use Mockery;
use phpmock\Mock;
use phpmock\MockBuilder;
use ReflectionClass;

class UserRepositoryTest extends TestCase {

    /** @var UserRepositoryInterface */
    protected $repository;

    protected function setUp() {
        parent::setUp();

        $builder = new MockBuilder();
        $mock    = $builder
            ->setNamespace((new ReflectionClass(OAuthServiceProvider::class))->getNamespaceName())
            ->setName('config')
            ->setFunction(function(string $key, $default = null) { return $default; } )
            ->build();

        $mock->enable();
        $this->repository = new UserRepository($this->entityManagerMock, new StdLogger(), new BcryptHasher());
        $mock->disable();
    }

    public function testGetUserEntityByUserCredentials() {
        $client = new Client('test', '', null, GrantHelper::GRANT_TYPE_PASSWORD);
        $user   = new User('abc', (new BcryptHasher())->make('abc'));

        $expectation = $this->entityManagerMock->shouldReceive('getRepository')
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

        $out = $this->repository
            ->getUserEntityByUserCredentials(
                $user->getIdentifier(),
                'abc',
                'password',
                $client
            );

        $this->assertSame($user, $out);
        $expectation->verify();
    }

    public function testGetUserEntityByUserCredentialsInvalidGrant() {
        $client = new Client('test', '', null, GrantHelper::GRANT_TYPE_PASSWORD);

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
        $user = new User('abc', 'test', (new BcryptHasher())->make('abc'));

        $expectation = $this->entityManagerMock->shouldReceive('getRepository')
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
        $expectation->verify();
        $this->assertSame($out, $user);
    }

    public function testGetUserByIdentifierNone() {
        $expectation = $this->entityManagerMock->shouldReceive('getRepository')
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
        $expectation->verify();
        $this->assertNull($out);
    }

}
