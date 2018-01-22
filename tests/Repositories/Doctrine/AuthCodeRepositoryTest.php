<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AuthCodeRepositoryTest.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\OAuth\Lumen\Tests\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Jitesoft\Log\NullLogger;
use Jitesoft\OAuth\Lumen\Entities\AuthCode;
use Jitesoft\OAuth\Lumen\Repositories\Doctrine\AuthCodeRepository;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
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
        $this->repository->persistNewAuthCode(new AuthCode());
    }

    public function testPersistNewAuthCodeError() {
        $code = new AuthCode();

        $this->repository->persistNewAuthCode($code);
        $this->repository->persistNewAuthCode($code);
    }

    public function testRevokeAuthCode() {

        $this->repository->revokeAuthCode('1');
        $this->repository->revokeAuthCode(2);
    }

    public function testIsAuthCodeRevoked() {
        $this->assertTrue($this->repository->isAuthCodeRevoked(1));
        $this->assertFalse($this->repository->isAuthCodeRevoked(2));
    }

}
