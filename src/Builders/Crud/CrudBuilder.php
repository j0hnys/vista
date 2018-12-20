<?php

namespace j0hnys\Vista\Builders\Crud;

class CrudBuilder
{
    
    /**
     * Crud constructor.
     * @param string $name
     * @throws \Exception
     */
    public function __construct(string $name = '', string $model_schema_relative_fullpath = '', string $resources_relative_path_name_ = '')
    {
        
        $mustache = new \Mustache_Engine;
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
            // $model_schema = file_get_contents(base_path().'/app/Models/Schemas/Exports/'.ucfirst(strtolower($name)).'.json');
            $model_schema = file_get_contents(base_path().$model_schema_relative_fullpath);
            $model_schema = json_decode($model_schema,true);
        } else {
            $model_schema = $this->defaultSchema();
        }
        $model_schema_parameters = array_map(function($element){
            return [
                'parameter_name' => $element['column_name'],
            ];
        },$model_schema);


        //
        //list delete generation
        $list_delete_path = base_path().'/'.$resources_relative_path_name.'/js/pages/'.strtolower($name).'_list_delete.vue';
        
        if (!file_exists($list_delete_path)) {
            $this->makeDirectory($list_delete_path);

            $stub = file_get_contents(__DIR__.'/../../Stubs/resources/js/pages/table_list_delete.vue.stub');
            
            //MIX_BASE_RELATIVE_URL
            $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
            $stub = str_replace('{{vst_entity}}', lcfirst($name), $stub);
            $stub = str_replace('{{Vst_entity}}', ucfirst($name), $stub);
            $stub = $mustache->render($stub, [
                'table_columns' => $model_schema_parameters,
            ]);

            
            file_put_contents($list_delete_path, $stub);
        }

        //
        //create generation
        $create_path = base_path().'/'.$resources_relative_path_name.'/js/pages/'.strtolower($name).'_create.vue';
        
        if (!file_exists($create_path)) {
            $this->makeDirectory($create_path);

            $stub = file_get_contents(__DIR__.'/../../Stubs/resources/js/pages/form_create.vue.stub');

            $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
            $stub = str_replace('{{vst_entity}}', lcfirst($name), $stub);
            $stub = str_replace('{{Vst_entity}}', ucfirst($name), $stub);
            $stub = $mustache->render($stub, [
                'form_elements' => $model_schema_parameters,
                'form_data_parameters' => $model_schema_parameters,
                'validation_rules' => $model_schema_parameters,
            ]);
            
            file_put_contents($create_path, $stub);
        }

        //
        //update generation
        $update_path = base_path().'/'.$resources_relative_path_name.'/js/pages/'.strtolower($name).'_update.vue';
        
        if (!file_exists($update_path)) {
            $this->makeDirectory($update_path);

            $stub = file_get_contents(__DIR__.'/../../Stubs/resources/js/pages/form_update.vue.stub');

            $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
            $stub = str_replace('{{vst_entity}}', lcfirst($name), $stub);
            $stub = str_replace('{{Vst_entity}}', ucfirst($name), $stub);
            $stub = $mustache->render($stub, [
                'form_elements' => $model_schema_parameters,
                'form_data_parameters' => $model_schema_parameters,
                'validation_rules' => $model_schema_parameters,
            ]);
            
            file_put_contents($update_path, $stub);
        }


        //
        //update router
        $router_path = base_path().'/'.$resources_relative_path_name.'/js/router.js';
        
        $lines = file($router_path); 
        $last = sizeof($lines) - 1; 
        unset($lines[$last]);   //<-- removing the 2 last lines
        unset($lines[$last-1]); //<--

        $fp = fopen($router_path, 'w'); 
        fwrite($fp, implode('', $lines)); 
        fclose($fp); 

        $stub = file_get_contents(__DIR__.'/../../Stubs/Crud/route.js.stub');

        $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
        $stub = str_replace('{{vst_entity}}', lcfirst($name), $stub);
        $stub = str_replace('{{Vst_entity}}', ucfirst($name), $stub);
        
        file_put_contents($router_path, $stub, FILE_APPEND);
            
    }
    

     /**
     * Build the directory for the class if necessary.
     *
     * @param  string $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
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
                "type" => "fillable"
            ],
            [
                "column_name" => "integer_parameter",
                "column_type" => "integer",
                "type" => "fillable"
            ]
        ];
    }

    
    /**
     * Get code and save to disk
     * @return mixed
     * @throws \Exception
     */
    public function save()
    {
        //
    }

}