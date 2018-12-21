<?php

namespace j0hnys\Vista\Builders\Setup;

class Install
{
    
    /**
     * Install constructor.
     * 
     * @return void
     */
    public function __construct(string $resources_relative_path_name_ = '')
    {
        
        $app_path = base_path().'/app';

        $resources_relative_path_name = 'resources';
        $public_relative_path_name = 'public';
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

                    //set MIX_BASE_RELATIVE_URL
                    if (!empty($spa_configuration['mix_base_relative_url_env_name'])) {
                        $MIX_BASE_RELATIVE_URL = $spa_configuration['mix_base_relative_url_env_name'];
                    }  else {
                        throw new \Exception("no \"mix_base_relative_url_env_name\" found in spa", 1);
                    }

                    //set public_relative_path_name
                    if (!empty($spa_configuration['public_folder_name'])) {
                        $public_relative_path_name = $spa_configuration['public_folder_name'];
                    } else {
                        throw new \Exception("public_folder_name must be set with resource_folder_name!!", 1);
                    }
                }
            } else {
                throw new \Exception("no \"resource_folder_name\" found in spa", 1);
            }
        }

        //
        //folder structure creation
        if (!file_exists(base_path().'/'.$resources_relative_path_name.'/js/app.js')) {
            
            //resources folder 
            $source = __DIR__.'/../../../scaffold_structure/resources';
            $destination = base_path().'/'.$resources_relative_path_name.'';
            $this->makeDirectory($destination);
            
            $this->copyFoldersAndFiles($source, $destination);
            
            //public folder
            $source = __DIR__.'/../../../scaffold_structure/public';
            $destination = base_path().'/'.$public_relative_path_name.'';
            $this->makeDirectory($destination);

            $this->copyFoldersAndFiles($source, $destination);

        } else {
            throw new \Exception("Already installed!!", 1);
        }


        //
        //write router.js
        $router_full_path = base_path().'/'.$resources_relative_path_name.'/js/router.js';
        
        $stub = file_get_contents(__DIR__.'/../../Stubs/resources/js/router.js.stub');
        $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
        
        file_put_contents($router_full_path, $stub);
        
        

        //
        //write spa controller
        $name = 'Spa';

        $controller_path = base_path().'/app/Http/Controllers/'.ucfirst(strtolower($name)).'Controller.php';
        
        if (!file_exists($controller_path)) {

            $stub = file_get_contents(__DIR__.'/../../Stubs/Crud/Controller.stub');

            $stub = str_replace('{{td_entity}}', strtolower($name), $stub);
            $stub = str_replace('{{Td_entity}}', ucfirst(strtolower($name)), $stub);
            
            file_put_contents($controller_path, $stub);
        }


        //
        //write resource routes file
        $Vista_base_repository_path = base_path().'/routes/web.php';
        $spa_get_urls = "\n".'Route::get(\'/{any}\', \'SpaController@index\')->where(\'any\', \'.*\');';
        file_put_contents($Vista_base_repository_path, $spa_get_urls, FILE_APPEND);



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
     * Build directory structure from copying another.
     *
     * @param  string $path
     * @return string
     */
    protected function copyFoldersAndFiles(string $source, string $destination)
    {
        if (!file_exists($destination)) {
            mkdir($destination, 0755);
        }
        foreach (
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                if (!file_exists( $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName() )) {
                    mkdir($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                }
            } else {
                copy($item, $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }

    }


}