<?php

namespace j0hnys\Vista\Console\Commands;

use Illuminate\Console\Command;
use j0hnys\Vista\Builders\Crud;

class GenerateCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "vista:generate:crud {name} {--schema_path=} {--resources_relative_path_name=}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Spa CRUD';

    /**
     * @var Crud\CrudBuilder
     */
    private $crud_builder;

    public function __construct(Crud\CrudBuilder $crud_builder = null)
    {
        parent::__construct();

        $this->crud_builder = new Crud\CrudBuilder();
        if (!empty($crud_builder)) {
            $this->crud_builder = $crud_builder;
        }
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $name = $this->argument('name');
            $schema_path = $this->option('schema_path') ? $this->option('schema_path') : '';
            $resources_relative_path_name = $this->option('resources_relative_path_name') ? $this->option('resources_relative_path_name') : '';
            
            $this->crud_builder->generate($name, $schema_path, $resources_relative_path_name);
            
            $this->info($name.' CRUD successfully created');            
        } catch (\Exception $ex) {
            $this->error($ex->getMessage() . ' on line ' . $ex->getLine() . ' in ' . $ex->getFile());
        }
    }

}