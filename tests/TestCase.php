<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  TestCase.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace Jitesoft\Loauthd\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use phpmock\Mock;

class TestCase extends \PHPUnit\Framework\TestCase {

    /** @var vfsStreamDirectory */
    protected $fileSystem;

    /** @var EntityManagerInterface|Mockery\Mock */
    protected $entityManagerMock;

    protected function tearDown() {
        parent::tearDown();
        Mock::disableAll();
        Mockery::close();
    }

    protected function setUp() {
        parent::setUp();

        $this->entityManagerMock = Mockery::mock(EntityManagerInterface::class);

        // Set up filesystem.
        $fs               = [ '/app' ];
        $this->fileSystem = vfsStream::setup('root', null, $fs);
    }

}
