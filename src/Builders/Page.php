<?php

namespace j0hnys\Vista\Builders;

use Illuminate\Container\Container as App;
use j0hnys\Vista\Base\Storage\Disk;

class Page
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
            $model_schema = $this->defaultSchema( lcfirst($name) );
        }


        //
        //list delete generation
        $this->listDelete($resources_relative_path_name, $MIX_BASE_RELATIVE_URL, $name, $model_schema);

        //
        //create generation
        $this->create($resources_relative_path_name, $MIX_BASE_RELATIVE_URL, $name, $model_schema);
        
        //
        //update generation
        $this->update($resources_relative_path_name, $MIX_BASE_RELATIVE_URL, $name, $model_schema);


    }

    /**
     * generates list delete page with accompanied data structures
     *
     * @param string $resources_relative_path_name
     * @param string $MIX_BASE_RELATIVE_URL
     * @param string $name
     * @param array $model_schema
     * @return void
     */
    public function listDelete(string $resources_relative_path_name, string $MIX_BASE_RELATIVE_URL, string $name, array $model_schema): void
    {
        $page_component_name = $name.'_list_delete';
        $page_api_server_namespace = 'application/api/server/'.$name.'_list_delete';
        $page_namespace = 'presentation/page/'.$name.'_list_delete_page';
        $page_name = $name.'_list_delete_page';
        $page_component_namespace = 'presentation/components/'.$name.'_list_delete';
        $mixin_namespace = 'presentation/mixins/'.$name.'_list_delete';
        $model_type_namespace = 'presentation/models/Types/'.$name.'_list_delete';
        $model_type = [
            [
                'property_name' => 'variable1',
                "property_value" => "String",
            ]
        ];

        $list_delete_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/pages/'.strtolower($name).'_list_delete.vue';
        $list_delete_api_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/application/api/server/'.strtolower($name).'_list_delete.js';
        $list_delete_component_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/components/'.strtolower($name).'_list_delete.vue';
        $list_delete_mixin_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/mixins/'.strtolower($name).'_list_delete.js';
        $list_delete_model_type_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/models/Types/'.strtolower($name).'_list_delete.js';
        $list_delete_store_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/stores/pages/'.lcfirst($name).'_list_delete.js';
        
        //page
        if (!$this->storage_disk->fileExists($list_delete_path)) {
            $this->storage_disk->makeDirectory($list_delete_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/pages/table_list_delete.vue.stub');
            
            $stub = str_replace('{{vst_entity}}', lcfirst($name), $stub);
            $stub = $this->mustache->render($stub, [
                'page_namespace' => $page_namespace,
                'page_name' => $page_name,
                'page_api_server_namespace' => $page_api_server_namespace,
                'page_component_name' => $page_component_name,
            ]);
            
            $this->storage_disk->writeFile($list_delete_path, $stub);
        }

        //api
        if (!$this->storage_disk->fileExists($list_delete_api_path)) {
            $this->storage_disk->makeDirectory($list_delete_api_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/client_app/application/api/server/table_list_delete.js.stub');
            
            $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
            $stub = $this->mustache->render($stub, [
                'ajax_get_get' => $model_schema['ajax']['get']['GET'],
                'ajax_delete_delete' => $model_schema['ajax']['delete']['DELETE'],
                'namespace' => $page_api_server_namespace,
                'name' => $page_component_name,
            ]);
            
            $this->storage_disk->writeFile($list_delete_api_path, $stub);
        }

        //component
        if (!$this->storage_disk->fileExists($list_delete_component_path)) {
            $this->storage_disk->makeDirectory($list_delete_component_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/components/table.vue.stub');
            
            $stub = $this->mustache->render($stub, [
                'component_name' => $page_component_name,
                'component_namespace' => $page_component_namespace,
                'table_columns' => $model_schema['presentation']['schema'],
            ]);
            
            $this->storage_disk->writeFile($list_delete_component_path, $stub);
        }

        //mixin
        if (!$this->storage_disk->fileExists($list_delete_mixin_path)) {
            $this->storage_disk->makeDirectory($list_delete_mixin_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/mixins/mixin.js.stub');
            
            $stub = $this->mustache->render($stub, [
                'mixin_namespace' => $mixin_namespace,
                'mixin_name' => $page_component_name,
            ]);
            
            $this->storage_disk->writeFile($list_delete_mixin_path, $stub);
        }

        //model type
        if (!$this->storage_disk->fileExists($list_delete_model_type_path)) {
            $this->storage_disk->makeDirectory($list_delete_model_type_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/models/Types/type.js.stub');
            
            $stub = $this->mustache->render($stub, [
                'model_type_namespace' => $model_type_namespace,
            ]);
            
            $this->storage_disk->writeFile($list_delete_model_type_path, $stub);
        }

        //store
        if (!$this->storage_disk->fileExists($list_delete_store_path)) {
            $this->storage_disk->makeDirectory($list_delete_store_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/stores/pages/FormPage.js.stub');
            
            $stub = $this->mustache->render($stub, [
                'type_parameters' => $model_type,
            ]);
            
            $this->storage_disk->writeFile($list_delete_store_path, $stub);
        }
    }

    /**
     * generates create page with accompanied data structures
     *
     * @param string $resources_relative_path_name
     * @param string $MIX_BASE_RELATIVE_URL
     * @param string $name
     * @param array $model_schema
     * @return void
     */
    public function create(string $resources_relative_path_name, string $MIX_BASE_RELATIVE_URL, string $name, array $model_schema): void
    {
        $page_component_name = $name.'_create';
        $page_api_server_namespace = 'application/api/server/'.$name.'_create';
        $page_namespace = 'presentation/page/'.$name.'_create_page';
        $page_name = $name.'_create_page';
        $page_component_namespace = 'presentation/components/'.$name.'_create';
        $mixin_namespace = 'presentation/mixins/'.$name.'_create';
        $model_type_namespace = 'presentation/models/Types/'.$name.'_create';
        $model_type = [
            [
                'property_name' => 'variable1',
                "property_value" => "String",
            ]
        ];

        $create_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/pages/'.strtolower($name).'_create.vue';
        $create_api_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/application/api/server/'.strtolower($name).'_create.js';
        $create_component_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/components/'.strtolower($name).'_create.vue';
        $create_mixin_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/mixins/'.strtolower($name).'_create.js';
        $create_model_type_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/models/Types/'.strtolower($name).'_create.js';
        $create_store_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/stores/pages/'.lcfirst($name).'_create.js';
        
        //page
        if (!$this->storage_disk->fileExists($create_path)) {
            $this->storage_disk->makeDirectory($create_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/pages/form_create.vue.stub');
            
            $stub = str_replace('{{vst_entity}}', lcfirst($name), $stub);
            $stub = $this->mustache->render($stub, [
                'page_namespace' => $page_namespace,
                'page_name' => $page_name,
                'page_api_server_namespace' => $page_api_server_namespace,
                'page_component_name' => $page_component_name,
            ]);
            
            $this->storage_disk->writeFile($create_path, $stub);
        }

        //api
        if (!$this->storage_disk->fileExists($create_api_path)) {
            $this->storage_disk->makeDirectory($create_api_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/client_app/application/api/server/form_create.js.stub');
            
            $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
            $stub = $this->mustache->render($stub, [
                'ajax_create_post' => $model_schema['ajax']['create']['POST'],
                'namespace' => $page_api_server_namespace,
                'name' => $page_component_name,
            ]);
            
            $this->storage_disk->writeFile($create_api_path, $stub);
        }

        //component
        if (!$this->storage_disk->fileExists($create_component_path)) {
            $this->storage_disk->makeDirectory($create_component_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/components/form.vue.stub');
            
            $stub = $this->mustache->render($stub, [
                'component_name' => $page_component_name,
                'component_namespace' => $page_component_namespace,
                'form_elements' => $model_schema['presentation']['schema'],
                'form_data_parameters' => $model_schema['presentation']['schema'],
                'validation_rules' => $model_schema['presentation']['schema'],
            ]);
            
            $this->storage_disk->writeFile($create_component_path, $stub);
        }

        //mixin
        if (!$this->storage_disk->fileExists($create_mixin_path)) {
            $this->storage_disk->makeDirectory($create_mixin_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/mixins/mixin.js.stub');
            
            $stub = $this->mustache->render($stub, [
                'mixin_namespace' => $mixin_namespace,
                'mixin_name' => $page_component_name,
            ]);
            
            $this->storage_disk->writeFile($create_mixin_path, $stub);
        }

        //model type
        if (!$this->storage_disk->fileExists($create_model_type_path)) {
            $this->storage_disk->makeDirectory($create_model_type_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/models/Types/type.js.stub');
            
            $stub = $this->mustache->render($stub, [
                'model_type_namespace' => $model_type_namespace,
            ]);
            
            $this->storage_disk->writeFile($create_model_type_path, $stub);
        }

        //store
        if (!$this->storage_disk->fileExists($create_store_path)) {
            $this->storage_disk->makeDirectory($create_store_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/stores/pages/FormPage.js.stub');
            
            $stub = $this->mustache->render($stub, [
                'type_parameters' => $model_type,
                'form_data_parameters' => $model_schema['presentation']['schema'],
            ]);
            
            $this->storage_disk->writeFile($create_store_path, $stub);
        }
    }

    /**
     * generates update page with accompanied data structures
     *
     * @param string $resources_relative_path_name
     * @param string $MIX_BASE_RELATIVE_URL
     * @param string $name
     * @param array $model_schema
     * @return void
     */
    public function update(string $resources_relative_path_name, string $MIX_BASE_RELATIVE_URL, string $name, array $model_schema): void
    {
        $page_component_name = $name.'_update';
        $page_api_server_namespace = 'application/api/server/'.$name.'_update';
        $page_namespace = 'presentation/page/'.$name.'_update_page';
        $page_name = $name.'_update_page';
        $page_component_namespace = 'presentation/components/'.$name.'_update';
        $mixin_namespace = 'presentation/mixins/'.$name.'_update';
        $model_type_namespace = 'presentation/models/Types/'.$name.'_update';
        $store_page_namespace = 'pages/'.$name.'_update';
        $model_type = [
            [
                'property_name' => 'variable1',
                "property_value" => "String",
            ]
        ];

        $update_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/pages/'.strtolower($name).'_update.vue';
        $update_api_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/application/api/server/'.strtolower($name).'_update.js';
        $update_component_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/components/'.strtolower($name).'_update.vue';
        $update_mixin_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/mixins/'.strtolower($name).'_update.js';
        $update_model_type_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/models/Types/'.strtolower($name).'_update.js';
        $update_store_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/presentation/stores/pages/'.lcfirst($name).'_update.js';
        
        //page
        if (!$this->storage_disk->fileExists($update_path)) {
            $this->storage_disk->makeDirectory($update_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/pages/form_update.vue.stub');
            
            $stub = str_replace('{{vst_entity}}', lcfirst($name), $stub);
            $stub = $this->mustache->render($stub, [
                'page_namespace' => $page_namespace,
                'page_name' => $page_name,
                'page_api_server_namespace' => $page_api_server_namespace,
                'page_component_name' => $page_component_name,
                'store_page_namespace' => $store_page_namespace,
            ]);
            
            $this->storage_disk->writeFile($update_path, $stub);
        }

        //api
        if (!$this->storage_disk->fileExists($update_api_path)) {
            $this->storage_disk->makeDirectory($update_api_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/client_app/application/api/server/form_update.js.stub');
            
            $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
            $stub = $this->mustache->render($stub, [
                'ajax_get_get' => $model_schema['ajax']['get']['GET'],
                'ajax_update_post' => $model_schema['ajax']['update']['POST'],
                'namespace' => $page_api_server_namespace,
                'name' => $page_component_name,
            ]);
            
            $this->storage_disk->writeFile($update_api_path, $stub);
        }

        //component
        if (!$this->storage_disk->fileExists($update_component_path)) {
            $this->storage_disk->makeDirectory($update_component_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/components/form.vue.stub');
            
            $stub = $this->mustache->render($stub, [
                'component_name' => $page_component_name,
                'component_namespace' => $page_component_namespace,
                'form_elements' => $model_schema['presentation']['schema'],
                'form_data_parameters' => $model_schema['presentation']['schema'],
                'validation_rules' => $model_schema['presentation']['schema'],
            ]);
            
            $this->storage_disk->writeFile($update_component_path, $stub);
        }

        //mixin
        if (!$this->storage_disk->fileExists($update_mixin_path)) {
            $this->storage_disk->makeDirectory($update_mixin_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/mixins/mixin.js.stub');
            
            $stub = $this->mustache->render($stub, [
                'mixin_namespace' => $mixin_namespace,
                'mixin_name' => $page_component_name,
            ]);
            
            $this->storage_disk->writeFile($update_mixin_path, $stub);
        }

        //model type
        if (!$this->storage_disk->fileExists($update_model_type_path)) {
            $this->storage_disk->makeDirectory($update_model_type_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/models/Types/type.js.stub');
            
            $stub = $this->mustache->render($stub, [
                'model_type_namespace' => $model_type_namespace,
            ]);
            
            $this->storage_disk->writeFile($update_model_type_path, $stub);
        }

        //store
        if (!$this->storage_disk->fileExists($update_store_path)) {
            $this->storage_disk->makeDirectory($update_store_path);

            $stub = $this->storage_disk->readFile(__DIR__.'/../Stubs/resources/js/stores/pages/FormPage.js.stub');
            
            $stub = $this->mustache->render($stub, [
                'type_parameters' => $model_type,
                'form_data_parameters' => $model_schema['presentation']['schema'],
            ]);
            
            $this->storage_disk->writeFile($update_store_path, $stub);
        }

    }

    /**
     * return the names of all events from trigger folder. (assumes that the namespace conventions are applied)
     *
     * @return array
     */
    public function defaultSchema(string $vst_entity)
    {
        return [
            'ajax' => [
                'get' => [
                    'GET' => '/trident/resource/'.$vst_entity
                ],
                'create' => [
                    'POST' => '/trident/resource/'.$vst_entity
                ],
                'update' => [
                    'POST' => '/trident/resource/'.$vst_entity
                ],
                'delete' => [
                    'DELETE' => '/trident/resource/'.$vst_entity
                ],
            ],
            'presentation' => [
                'type' => 'form',
                'schema' => [
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
                            "default_value" => '[\'eat\']',
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
                ]
            ]
        ];
    }


}
