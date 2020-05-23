<?php

namespace j0hnys\Vista\Console\Commands;

use Illuminate\Console\Command;
use j0hnys\Vista\Builders\Page;

class GeneratePage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "vista:generate:page {name} {--schema_path=} {--resources_relative_path_name=}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a page with everything';

    /**
     * @var Page
     */
    private $page_builder;

    public function __construct(Page $page_builder = null)
    {
        parent::__construct();

        $this->page_builder = new Page();
        if (!empty($page_builder)) {
            $this->page_builder = $page_builder;
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

            $this->page_builder->generate($name, $schema_path, $resources_relative_path_name);

            $this->info($name.' page successfully created');            
        } catch (\Exception $ex) {
            $this->error($ex->getMessage() . ' on line ' . $ex->getLine() . ' in ' . $ex->getFile());
        }
    }

}