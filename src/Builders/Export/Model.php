<?php

namespace j0hnys\Vista\Builders\Export;

use Illuminate\Container\Container as App;
use j0hnys\Vista\Base\Storage\Disk;

class Model
{
    private $mustache;
    private $storage_disk;
    
    public function __construct(Disk $storage_disk = null)
    {
        $this->mustache = new \Mustache_Engine;
        $this->storage_disk = new Disk();
        if (!empty($storage_disk)) {
            $this->storage_disk = $storage_disk;
        }
        $this->app = new App();
    }
    
    /**
     * Crud constructor.
     * @param string $name
     * @throws \Exception
     */
    public function generate($td_entity_name, $td_entity_namespace, $output_relative_path = null)
    {
        $model = $this->app->make($td_entity_namespace);

        $db_table_name = $model->getTable();
        $db_table_fillables = $model->getFillable();

        $tmp = [];
        foreach ($db_table_fillables as $key => $db_table_fillable) {
            $tmp []= $this->schemaItem($db_table_fillable, \Schema::getColumnType($db_table_name, $db_table_fillable));
        }

        $schema = [
            'ajax' => [
                'get' => [
                    'GET' => '/trident/resource/'.$td_entity_name
                ],
                'create' => [
                    'POST' => '/trident/resource/'.$td_entity_name
                ],
                'update' => [
                    'POST' => '/trident/resource/'.$td_entity_name
                ],
                'delete' => [
                    'DELETE' => '/trident/resource/'.$td_entity_name
                ],
            ],
            'presentation' => [
                'type' => 'form',
                'schema' => $tmp
            ]
        ];

        //
        //export
        $output_relative_path_ = '/app/Models/Schemas/Exports/'.$td_entity_name.'.json';
        if ($output_relative_path) {
            $output_relative_path_ = $output_relative_path;
        }
        $schema_export_path = $this->storage_disk->getBasePath().$output_relative_path_;
        $this->storage_disk->makeDirectory($schema_export_path);

        $this->storage_disk->writeFile($schema_export_path, json_encode($schema,JSON_PRETTY_PRINT));        

    }
    
    /**
     * @param string $column_name
     * @param string $column_type
     * @return array
     */
    protected function schemaItem(string $column_name = '', string $column_type = ''): array
    {
        $item = [
            'column_name' => $column_name,
            'column_type' => $column_type,
            'type' => 'fillable',
        ];

        if ($column_type == 'string') {
            $item['validation_rules'] = [
                "required" => true,
                "type" => "string",
                "trigger" => "blur",
            ];
            $item['attributes'] = [
                "type" => ["string" => true],
                "default_value" => '\'\'',
                "element_type" => false,
            ];
        } else if ($column_type == 'integer') {
            $item['validation_rules'] = [
                "required" => true,
                "type" => "number",
                "trigger" => "blur",
            ];
            $item['attributes'] = [
                "type" => ["number" => true],
                "default_value" => '0',
                "element_type" => false,
            ];
        }

        return $item;
    }

}