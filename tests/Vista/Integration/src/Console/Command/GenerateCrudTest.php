<?php

namespace j0hnys\Vista\Tests\Integration;

use j0hnys\Vista\Tests\Base\TestCase;
use j0hnys\Vista\Builders\Setup\Install;
use j0hnys\Vista\Builders\Crud\CrudBuilder;
use j0hnys\Vista\Console\Commands\GenerateCrud as GenerateCrudCommand;

class GenerateCrudTest extends TestCase
{
    private $install;
    private $crud_builder;

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

        //crud builder
        $this->crud_builder = new CrudBuilder( $this->storage_disk );

        //command behavioural test
        $this->mock_crud_builder = $this->createMock(CrudBuilder::class);
        $this->mock_command_crud_builder = $this->getMockBuilder(GenerateCrudCommand::class)
            ->setConstructorArgs([$this->mock_crud_builder])
            ->setMethods(['argument','option','info'])
            ->getMock();
    }


    public function testHandle()
    {
        $name = '';
        $schema_path = '';
        $resources_relative_path_name = '';

        $this->mock_command_crud_builder->expects($this->at(0))
            ->method('argument')
            ->willReturn($name);

        $this->mock_command_crud_builder->expects($this->at(0))
            ->method('option')
            ->willReturn($schema_path);

        $this->mock_command_crud_builder->expects($this->at(1))
            ->method('option')
            ->willReturn($resources_relative_path_name);

        $this->mock_command_crud_builder->handle();

        //assert
        $this->assertTrue(true);
    }

    
    public function testGenerate()
    {
        $name = 'DemoProcess';
        $schema_path = '';
        $resources_relative_path_name = '';            

        $this->crud_builder->generate($name, $schema_path, $resources_relative_path_name);

        $this->assertTrue(true);
    }
}
