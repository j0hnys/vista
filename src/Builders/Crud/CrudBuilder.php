<?php

namespace j0hnys\Vista\Builders\Crud;

use Illuminate\Container\Container as App;
use j0hnys\Vista\Base\Storage\Disk;
use j0hnys\Vista\Base\Utilities\WordCaseConverter;
use j0hnys\Vista\Builders\Page;

class CrudBuilder
{
    private $mustache;
    private $storage_disk;
    private $word_case_converter;
    private $page;
    
    public function __construct(Disk $storage_disk = null, Page $page = null)
    {
        $this->mustache = new \Mustache_Engine;
        $this->storage_disk = new Disk();
        if (!empty($storage_disk)) {
            $this->storage_disk = $storage_disk;
        }
        $this->page = new Page($this->storage_disk);
        if (!empty($page)) {
            $this->page = $page;
        }
        $this->app = new App();
        $this->word_case_converter = new WordCaseConverter();
    }

    
    /**
     * Crud constructor.
     * @param string $name
     * @throws \Exception
     */
    public function generate(string $name = '', string $model_schema_relative_fullpath = '', string $resources_relative_path_name_ = '')
    {
        $browser_local_storage_key = 'vista';
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

                if (!empty($spa_configuration['browser_local_storage_key'])) {
                    $browser_local_storage_key = $spa_configuration['browser_local_storage_key'];
                }
            } else {
                throw new \Exception("no \"resource_folder_name\" found in spa", 1);
            }
        }


        if (!empty($model_schema_relative_fullpath)) {
            $model_schema = $this->storage_disk->readFile($this->storage_disk->getBasePath().$model_schema_relative_fullpath);
            $model_schema = json_decode($model_schema,true);
        } else {
            $model_schema = $this->page->defaultSchema( ($name) );
        }


        //
        //list delete generation
        $this->page->listDelete($resources_relative_path_name, $MIX_BASE_RELATIVE_URL, $name, $model_schema);

        //
        //create generation
        $this->page->create($resources_relative_path_name, $MIX_BASE_RELATIVE_URL, $name, $model_schema);

        //
        //update generation
        $this->page->update($resources_relative_path_name, $MIX_BASE_RELATIVE_URL, $name, $model_schema);
        

        //
        //update routes
        $route_pages_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/application/router.pages.js';
        
        $lines = $this->storage_disk->readFileArray($route_pages_path); 
        $lines []= str_replace('{{vst_entity}}', ($name), "\n".'exports.{{vst_entity}}ListDelete = require("../presentation/pages/{{vst_entity}}ListDelete.vue").default;');
        $lines []= str_replace('{{vst_entity}}', ($name), "\n".'exports.{{vst_entity}}Create = require("../presentation/pages/{{vst_entity}}Create.vue").default;');
        $lines []= str_replace('{{vst_entity}}', ($name), "\n".'exports.{{vst_entity}}Update = require("../presentation/pages/{{vst_entity}}Update.vue").default;');
        
        $this->storage_disk->writeFileArray($route_pages_path, $lines); 


        //
        //update router
        $router_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/application/router.js';
        
        $lines = $this->storage_disk->readFileArray($router_path); 
        $last = sizeof($lines) - 1; 
        unset($lines[$last]);   //<-- removing the 2 last lines
        unset($lines[$last-1]); //<--

        $this->storage_disk->writeFileArray($router_path, $lines); 
        
        $stub = $this->storage_disk->readFile(__DIR__.'/../../Stubs/Crud/route.js.stub');

        $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
        $stub = str_replace('{{list_delete_uri}}', $this->word_case_converter->camelCaseToSnakeCase($name.'_list'), $stub);
        $stub = str_replace('{{list_delete_component_name}}', $this->word_case_converter->snakeAndKebabCaseToPascalCase($name.'_list_delete'), $stub);
        $stub = str_replace('{{create_uri}}', $this->word_case_converter->camelCaseToSnakeCase($name.'_create'), $stub);
        $stub = str_replace('{{create_component_name}}', $this->word_case_converter->snakeAndKebabCaseToPascalCase($name.'_create'), $stub);
        $stub = str_replace('{{update_uri}}', $this->word_case_converter->camelCaseToSnakeCase($name.'_update'), $stub);
        $stub = str_replace('{{update_component_name}}', $this->word_case_converter->snakeAndKebabCaseToPascalCase($name.'_update'), $stub);
        
        $this->storage_disk->writeFile($router_path, $stub, [
            'append_file' => true,
        ]);


        //
        //update store pages
        $store_pages_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/application/store.pages.js';
        
        $lines = $this->storage_disk->readFileArray($store_pages_path); 
        $lines []= str_replace('{{vst_entity}}', ($name), "\n".'exports.{{vst_entity}}ListDelete = require("../presentation/stores/pages/{{vst_entity}}ListDelete.js").default;');
        $lines []= str_replace('{{vst_entity}}', ($name), "\n".'exports.{{vst_entity}}Create = require("../presentation/stores/pages/{{vst_entity}}Create.js").default;');
        $lines []= str_replace('{{vst_entity}}', ($name), "\n".'exports.{{vst_entity}}Update = require("../presentation/stores/pages/{{vst_entity}}Update.js").default;');
        
        $this->storage_disk->writeFileArray($store_pages_path, $lines);

        //
        //update store
        $store_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/application/store.js';
        $store_pages_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/application/store.pages.js';

        $lines = $this->storage_disk->readFileArray($store_pages_path);

        $store_pages = [
            'browser_local_storage_key' => $browser_local_storage_key,
            'page_modules' => []
        ];
        foreach ($lines as $line) {
            if (preg_match('/exports\.(.*?) = require/', $line, $match) == 1) {
                if ($match[1] == 'index') {
                    continue;
                }
                $store_pages['page_modules'] []= [
                    'module_name' => $match[1],
                ];
            }
        }

        $stub = $this->storage_disk->readFile(__DIR__.'/../../Stubs/resources/js/store.js.stub');

        $stub = $this->mustache->render($stub, $store_pages);
        
        $this->storage_disk->writeFile($store_path, $stub);

    }


}
