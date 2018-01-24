<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  KeyGenerateCommandTest.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Tests\Commands;

use Jitesoft\Exceptions\IO\FileException;
use Jitesoft\Loauthd\Commands\KeyGenerateCommand;
use Jitesoft\Loauthd\Tests\TestCase;
use Mockery;
use phpmock\MockBuilder;

class KeyGenerateCommandTest extends TestCase {


    public function testGenerateKeys() {

        $builder = new MockBuilder();
        $mock    = $builder
            ->setNamespace((new \ReflectionClass(KeyGenerateCommand::class))->getNamespaceName())
            ->setName('storage_path')
            ->setFunction(function(string $path = '') {
                return $this->fileSystem->url() . '/storage/' . $path;
            }
        )->build();
        $mock->enable();

        /** @var Mockery\Mock|KeyGenerateCommand $cmd */
        $cmd = Mockery::mock(KeyGenerateCommand::class)->makePartial()->shouldReceive('info')->twice()->getMock();
        $cmd->handle();

        $this->assertFileExists($this->fileSystem->url() . '/storage/oauth/private.key');
        $this->assertFileExists($this->fileSystem->url() . '/storage/oauth/public.key');
    }

    public function testGenerateKeysKeysExist() {

        $builder = new MockBuilder();
        $mock    = $builder
            ->setNamespace((new \ReflectionClass(KeyGenerateCommand::class))->getNamespaceName())
            ->setName('storage_path')
            ->setFunction(function(string $path = '') {
                return $this->fileSystem->url() . '/storage/' . $path;
            }
            )->build();
        $mock->enable();

        /** @var Mockery\Mock|KeyGenerateCommand $cmd */
        $cmd = Mockery::mock(KeyGenerateCommand::class)->makePartial()->shouldReceive('info')->twice()->getMock();
        $cmd->handle();

        $this->expectException(FileException::class);
        $this->expectExceptionMessage('OAuth keys already exist.');

        $cmd = Mockery::mock(KeyGenerateCommand::class)->makePartial()->shouldReceive('info')->once()->getMock();
        $cmd->handle();

    }

}
