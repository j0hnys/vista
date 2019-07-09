<?php

namespace j0hnys\Vista\Builders\Crud;
use j0hnys\Vista\Base\Storage\Disk;

class CrudBuilder
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
    public function generate(string $name = '', string $model_schema_relative_fullpath = '', string $resources_relative_path_name_ = '')
    {
        
        $resources_relative_path_name = 'resources';
        if (!empty($resources_relative_path_name_)) {
            $resources_relative_path_name = $resources_relative_path_name_;
        }
        
        $configuration = config('vista');
        if (empty($configuration)) {
            throw new \Exception("NO CONFIGURATION FILE FOUND!! execute \"php artisan vendor:publish\"", 1);
        }

        $MIX_BASE_RELATIVE_URL = 'MIX_BASE_RELATIVE_URL';
        foreach ($configuration['spas'] as $spa_configuration) {
            if (!empty($spa_configuration['resource_folder_name'])) {
                if ($spa_configuration['resource_folder_name'] == $resources_relative_path_name) {
                    if (!empty($spa_configuration['mix_base_relative_url_env_name'])) {
                        $MIX_BASE_RELATIVE_URL = $spa_configuration['mix_base_relative_url_env_name'];
                    }  else {
                        throw new \Exception("no \"mix_base_relative_url_env_name\" found in spa", 1);
                    }
                }
            } else {
                throw new \Exception("no \"resource_folder_name\" found in spa", 1);
            }
        }


        if (!empty($model_schema_relative_fullpath)) {
            $model_schema = $this->storage_disk->readFile($this->storage_disk->getBasePath().$model_schema_relative_fullpath);
            $model_schema = json_decode($model_schema,true);
        } else {
            $model_schema = $this->defaultSchema();
        }


        //
        //list delete generation
        $list_delete_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/js/pages/'.strtolower($name).'_list_delete.vue';
        
        if (!$this->storage_disk->fileExists($list_delete_path)) {
            $this->storage_disk->makeDirectory($list_delete_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../../Stubs/resources/js/pages/table_list_delete.vue.stub');
            
            //MIX_BASE_RELATIVE_URL
            $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
            $stub = str_replace('{{vst_entity}}', lcfirst($name), $stub);
            $stub = str_replace('{{Vst_entity}}', ucfirst($name), $stub);
            $stub = $this->mustache->render($stub, [
                'table_columns' => $model_schema,
            ]);

            
            $this->storage_disk->writeFile($list_delete_path, $stub);
        }

        //
        //create generation
        $create_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/js/pages/'.strtolower($name).'_create.vue';
        
        if (!$this->storage_disk->fileExists($create_path)) {
            $this->storage_disk->makeDirectory($create_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../../Stubs/resources/js/pages/form_create.vue.stub');

            $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
            $stub = str_replace('{{vst_entity}}', lcfirst($name), $stub);
            $stub = str_replace('{{Vst_entity}}', ucfirst($name), $stub);
            $stub = $this->mustache->render($stub, [
                'form_elements' => $model_schema,
                'form_data_parameters' => $model_schema,
                'validation_rules' => $model_schema,
            ]);
            
            $this->storage_disk->writeFile($create_path, $stub);
        }

        //
        //update generation
        $update_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/js/pages/'.strtolower($name).'_update.vue';
        
        if (!$this->storage_disk->fileExists($update_path)) {
            $this->storage_disk->makeDirectory($update_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../../Stubs/resources/js/pages/form_update.vue.stub');

            $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
            $stub = str_replace('{{vst_entity}}', lcfirst($name), $stub);
            $stub = str_replace('{{Vst_entity}}', ucfirst($name), $stub);
            $stub = $this->mustache->render($stub, [
                'form_elements' => $model_schema,
                'form_data_parameters' => $model_schema,
                'validation_rules' => $model_schema,
            ]);
            
            $this->storage_disk->writeFile($update_path, $stub);
        }
        

        //
        //update routes
        $routes_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/js/router.pages.js';
        
        $lines = $this->storage_disk->readFileArray($routes_path); 
        $lines []= str_replace('{{vst_entity}}', lcfirst($name), "\n".'exports.{{vst_entity}}_list_delete = require("./pages/{{vst_entity}}_list_delete.vue").default;');
        $lines []= str_replace('{{vst_entity}}', lcfirst($name), "\n".'exports.{{vst_entity}}_create = require("./pages/{{vst_entity}}_create.vue").default;');
        $lines []= str_replace('{{vst_entity}}', lcfirst($name), "\n".'exports.{{vst_entity}}_update = require("./pages/{{vst_entity}}_update.vue").default;');
        
        $this->storage_disk->writeFileArray($routes_path, $lines); 


        //
        //update router
        $router_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/js/router.js';
        
        $lines = $this->storage_disk->readFileArray($router_path); 
        $last = sizeof($lines) - 1; 
        unset($lines[$last]);   //<-- removing the 2 last lines
        unset($lines[$last-1]); //<--

        $this->storage_disk->writeFileArray($router_path, $lines); 
        
        $stub = $this->storage_disk->readFile(__DIR__.'/../../Stubs/Crud/route.js.stub');

        $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
        $stub = str_replace('{{vst_entity}}', lcfirst($name), $stub);
        $stub = str_replace('{{Vst_entity}}', ucfirst($name), $stub);
        
        $this->storage_disk->writeFile($router_path, $stub, [
            'append_file' => true,
        ]);
            
    }

    /**
     * return the names of all events from trigger folder. (assumes that the namespace conventions are applied)
     *
     * @return array
     */
    public function defaultSchema()
    {
        return [
            [
                "column_name" => "string_parameter",
                "column_type" => "string",
                "type" => "fillable",
                "validation_rules" => [
                    [
                        "required" => true,
                        "type" => "string",
                        "trigger" => "blur",
                    ]
                ],
                "attributes" => [
                    "type" => ["string" => true],
                    "default_value" => '\'\'',
                    "element_type" => false,
                ]
            ],
            [
                "column_name" => "integer_parameter",
                "column_type" => "integer",
                "type" => "fillable",
                "validation_rules" => [
                    [
                        "required" => true,
                        "type" => "number",
                        "max" => 30,
                        "min" => 0,
                        "trigger" => "blur",
                    ]
                ],
                "attributes" => [
                    "type" => ["number" => true],
                    "default_value" => '0',
                    "element_type" => false,
                ]
            ],
            [
                "column_name" => "boolean_parameter",
                "column_type" => "boolean",
                "type" => "fillable",
                "validation_rules" => [
                    [
                        "required" => true,
                        "type" => "boolean",
                        "trigger" => "blur",
                    ]
                ],
                "attributes" => [
                    "type" => ["switch" => true],
                    "default_value" => 'true',
                    "element_type" => false,
                ],
                "fields" => [
                    [
                        "name" => "open", 
                        "text" => "on", 
                        "value" => "on", 
                    ],
                    [
                        "name" => "close", 
                        "text" => "off", 
                        "value" => "off", 
                    ],
                ],
            ],
            [
                "column_name" => "date_parameter",
                "column_type" => "date",
                "type" => "fillable",
                "validation_rules" => [
                    [
                        "required" => true,
                        "type" => "date",
                        "from" => "2018-12-12 00:00:00",
                        "to" => "2018-12-22 00:00:00",
                        "trigger" => "blur",
                    ]
                ],
                "attributes" => [
                    "type" => ["date" => true],
                    "default_value" => '\'\'',
                    "element_type" => 'datetime',
                ],
            ],
            [
                "column_name" => "text_parameter",
                "column_type" => "string",
                "type" => "fillable",
                "validation_rules" => [
                    [
                        "required" => true,
                        "type" => "text",
                        "trigger" => "blur",
                    ]
                ],
                "attributes" => [
                    "type" => ["text" => true],
                    "default_value" => '\'\'',
                    "element_type" => 'textarea',
                ]
            ],
            [
                "column_name" => "range_parameter",
                "column_type" => "float",
                "type" => "fillable",
                "validation_rules" => [
                    [
                        "required" => true,
                        "type" => "number",
                        "max" => 30,
                        "min" => 0,
                        "trigger" => "blur",
                    ],
                ],
                "attributes" => [
                    "type" => ["slider" => true],
                    "default_value" => '[5,15]',
                    "element_type" => false,
                    "precision" => 2,
                ]
            ],
            [
                "column_name" => "radio_parameter",
                "column_type" => "string",
                "type" => "fillable",
                "validation_rules" => [
                    [
                        "required" => true,
                        "type" => "radio",
                        "trigger" => "blur",
                    ]
                ],
                "attributes" => [
                    "type" => ["radio" => true],
                    "default_value" => '\'eat\'',
                    "element_type" => false,
                ],
                "fields" => [
                    [
                        "name" => "eat", 
                        "text" => "Eat", 
                        "value" => "Eat", 
                    ],
                    [
                        "name" => "sleep", 
                        "text" => "Sleep", 
                        "value" => "Sleep", 
                    ],
                    [
                        "name" => "repeat",
                        "text" => "Repeat",
                        "value" => "Repeat",
                    ],
                ],
            ],
            [
                "column_name" => "checkbox_parameter",
                "column_type" => "string",
                "type" => "fillable",
                "validation_rules" => [
                    [
                        "required" => true,
                        "type" => "checkbox",
                        "max" => 2,
                        "min" => 1,
                        "trigger" => "blur",
                    ]
                ],
                "attributes" => [
                    "type" => ["checkbox" => true],
                    "default_value" => '\'eat\'',
                    "element_type" => false,
                ],
                "fields" => [
                    [
                        "name" => "eat", 
                        "text" => "Eat", 
                        "value" => "Eat", 
                    ],
                    [
                        "name" => "sleep", 
                        "text" => "Sleep", 
                        "value" => "Sleep", 
                    ],
                    [
                        "name" => "repeat",
                        "text" => "Repeat",
                        "value" => "Repeat",
                    ],
                ],
            ],
            [
                "column_name" => "file_parameter",
                "column_type" => "string",
                "type" => "fillable",
                "validation_rules" => [
                    [
                        "required" => true,
                        "type" => "file",
                        "trigger" => "blur",
                    ]
                ],
                "attributes" => [
                    "type" => ["file" => true],
                    "default_value" => 'null',
                    "element_type" => false,
                ]
            ],
        ];
    }


}
