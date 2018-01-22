<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  TestCase.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace Jitesoft\OAuth\Lumen\Tests;

use Mockery;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use phpmock\Mock;

class TestCase extends \PHPUnit\Framework\TestCase {

    /** @var vfsStreamDirectory */
    protected $fileSystem;

    protected function tearDown() {
        parent::tearDown();
        Mock::disableAll();
        Mockery::close();
    }

    protected function setUp() {
        parent::setUp();
        // Set up filesystem.
        $fs               = [ '/app' ];
        $this->fileSystem = vfsStream::setup('root', null, $fs);
    }

}
