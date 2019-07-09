<?php

namespace j0hnys\Vista\Console\Commands;

use Illuminate\Console\Command;
use j0hnys\Vista\Builders\Export;

class ExportModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "vista:export:model {entity_name}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'export a models schema';

    /**
     * @var Export\Model
     */
    private $export_model;

    public function __construct(Export\Model $export_model = null)
    {
        parent::__construct();

        $this->export_model = new Export\Model();
        if (!empty($export_model)) {
            $this->export_model = $export_model;
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
            $entity_name = $this->argument('entity_name');

            $builders = $this->export_model->generate($entity_name);

            $this->info($entity_name.' model\'s export successful!');           
            
        } catch (\Exception $ex) {
            $this->error($ex->getMessage() . ' on line ' . $ex->getLine() . ' in ' . $ex->getFile());
        }
    }

}