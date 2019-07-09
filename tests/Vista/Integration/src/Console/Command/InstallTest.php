<?php

namespace j0hnys\Vista\Tests\Integration;

use j0hnys\Vista\Tests\Base\TestCase;
use j0hnys\Vista\Builders\Setup\Install;
use j0hnys\Vista\Console\Commands\Install as InstallCommand;

class InstallTest extends TestCase
{
    private $install;

    public function setUp(): void
    {
        parent::setUp();

        $this->install = new Install( $this->storage_disk );

        $this->storage_disk->makeDirectory($this->base_path.'/resources/.');
        $this->storage_disk->makeDirectory($this->base_path.'/public/.');        

        //command behavioural test
        $this->mock_install = $this->createMock(Install::class);
        $this->mock_command_install = $this->getMockBuilder(InstallCommand::class)
            ->setConstructorArgs([$this->mock_install])
            ->setMethods(['argument','option','info'])
            ->getMock();
    }


    public function testHandle()
    {

        $this->mock_command_install->handle();

        //assert
        $this->assertTrue(true);
    }

    
    public function testRun()
    {

        $this->install->run();

        $this->assertTrue(true);
    }
}
