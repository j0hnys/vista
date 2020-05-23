<?php

namespace j0hnys\Vista\Tests\Integration;

use j0hnys\Vista\Tests\Base\TestCase;
use j0hnys\Vista\Builders\Setup\Install;
use j0hnys\Vista\Builders\Page;

class PageTest extends TestCase
{
    private $install;
    private $page;

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

        $stub = $this->storage_disk->readFile($this->base_path.'/../Stubs/routes/web.php');
        $this->storage_disk->writeFile($this->base_path.'/routes/web.php', $stub);

        $this->install->run();

        //crud builder
        $this->page = new Page( $this->storage_disk );
    }
    
    public function testGenerate()
    {
        $name = 'DemoProcess';
        $schema_path = '/../Stubs/_Solution/Schemas/DemoProcess/Presentation/Form.json';
        $resources_relative_path_name = 'resources';

        $this->page->generate($name, $schema_path, $resources_relative_path_name);

        $this->assertTrue(true);
    }
}
