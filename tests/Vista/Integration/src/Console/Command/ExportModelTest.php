<?php

namespace j0hnys\Vista\Tests\Integration;

use j0hnys\Vista\Tests\Base\TestCase;
use j0hnys\Vista\Builders\Setup\Install;
use j0hnys\Vista\Builders\Export\Model;
use j0hnys\Vista\Console\Commands\ExportModel as ExportModelCommand;

class ExportModelTest extends TestCase
{
    private $install;
    private $export_model;

    public function setUp(): void
    {
        parent::setUp();

        //install
        $this->install = new Install( $this->storage_disk );

        $this->storage_disk->makeDirectory($this->base_path.'/resources/.');
        $this->storage_disk->makeDirectory($this->base_path.'/public/.');
        $this->storage_disk->makeDirectory($this->base_path.'/app/Http/Controllers/.');

        $stub = $this->storage_disk->readFile($this->base_path.'/../Stubs/.env');
        $this->storage_disk->writeFile($this->base_path.'/.env', $stub);

        $this->install->run();

        //export model
        $this->export_model = new Model( $this->storage_disk );

        $this->storage_disk->makeDirectory($this->base_path.'/app/Models/.');

        $stub = $this->storage_disk->readFile($this->base_path.'/../Stubs/App/Models/DemoProcess.stub');
        $this->storage_disk->writeFile($this->base_path.'/app/Models/DemoProcess.php', $stub);
        exec('composer dump-autoload');

        //command behavioural test
        $this->mock_export_model = $this->createMock(Model::class);
        $this->mock_command_export_model = $this->getMockBuilder(ExportModelCommand::class)
            ->setConstructorArgs([$this->mock_export_model])
            ->setMethods(['argument','option','info'])
            ->getMock();
    }


    public function testHandle()
    {
        $entity_name = '';
        $entity_namespace = '';

        $this->mock_command_export_model->expects($this->at(0))
            ->method('argument')
            ->willReturn($entity_name);

        $this->mock_command_export_model->expects($this->at(1))
            ->method('argument')
            ->willReturn($entity_namespace);

        $this->mock_command_export_model->handle();

        //assert
        $this->assertTrue(true);
    }

    
    public function testGenerate()
    {
        $entity_name = 'DemoProcess';
        $entity_namespace = 'App\\Models\\DemoProcess';

        $this->export_model->generate($entity_name, $entity_namespace);

        $this->assertTrue(true);
    }
}
