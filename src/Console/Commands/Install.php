<?php

namespace j0hnys\Vista\Console\Commands;

use Illuminate\Console\Command;
use j0hnys\Vista\Builders\Setup;

class Install extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "vista:install {resources_relative_path_name?}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vista installer';

    /**
     * @var Setup\Install
     */
    private $install;

    public function __construct(Setup\Install $install = null)
    {
        parent::__construct();

        $this->install = new Setup\Install();
        if (!empty($install)) {
            $this->install = $install;
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
            $resources_relative_path_name = $this->argument('resources_relative_path_name') ? $this->argument('resources_relative_path_name') : '';

            
            $install = $this->install->run($resources_relative_path_name);            

            
            $this->info('Vista installed successfully');
            
        } catch (\Exception $ex) {
            $this->error($ex->getMessage() . ' on line ' . $ex->getLine() . ' in ' . $ex->getFile());
        }
    }

}