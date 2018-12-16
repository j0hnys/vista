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
    protected $signature = "vista:generate:crud {name} {schema_path?} {resources_relative_path_name?} {--only=} {--api} {--parent=} ";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Spa CRUD';
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $name = $this->argument('name');
            $schema_path = $this->argument('schema_path') ? $this->argument('schema_path') : '';
            $resources_relative_path_name = $this->argument('resources_relative_path_name') ? $this->argument('resources_relative_path_name') : '';
            $only = $this->option('only');
            $api = $this->option('api');
            $withArr = !empty($with) ? explode(",", $with) : [];
            $onlyArr = !empty($only) ? explode(",", $only) : '';
            $parent = $this->option('parent');
           

            $crud = new Crud\CrudBuilder($name, $schema_path, $resources_relative_path_name);
            // $controllerCrud->save();


            $this->info($name.' CRUD successfully created');
            
        } catch (\Exception $ex) {
            $this->error($ex->getMessage() . ' on line ' . $ex->getLine() . ' in ' . $ex->getFile());
        }
    }

}