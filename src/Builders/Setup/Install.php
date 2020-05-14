<?php

namespace j0hnys\Vista\Builders\Setup;

use j0hnys\Vista\Base\Storage\Disk;

class Install
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
    }


    /**
     * Install constructor.
     * 
     * @return void
     */
    public function run(string $resources_relative_path_name_ = '')
    {
        
        $app_path = $this->storage_disk->getBasePath().'/app';
        $laravel_root_folder_name = pathinfo( base_path() )['basename'];
        $browser_local_storage_key = $laravel_root_folder_name;

        $resources_relative_path_name = 'resources';
        $public_relative_path_name = 'public';
        if (!empty($resources_relative_path_name_)) {
            $resources_relative_path_name = $resources_relative_path_name_;
        }

        $configuration = config('vista');
        if (empty($configuration)) {
            throw new \Exception("NO CONFIGURATION FILE FOUND!! execute \"php artisan vendor:publish\"", 1);
        }
        if (empty($configuration['spas'])) {
            throw new \Exception("configuration file property 'spas' should not be empty", 1);
        }

        $resource_public_folder_names = [];
        $MIX_BASE_URL = 'MIX_BASE_URL';
        $MIX_BASE_RELATIVE_URL = 'MIX_BASE_RELATIVE_URL';
        $MIX_STORAGE_URL = 'MIX_STORAGE_URL';
        foreach ($configuration['spas'] as $spa_configuration) {
            if (!empty($spa_configuration['browser_local_storage_key'])) {
                $browser_local_storage_key = $spa_configuration['browser_local_storage_key'];
            }
            if (!empty($spa_configuration['resource_folder_name'])) {
                if ($spa_configuration['resource_folder_name'] == $resources_relative_path_name) {

                    //set MIX_BASE_RELATIVE_URL
                    if (   !empty($spa_configuration['mix_base_url_env_name'])
                        && !empty($spa_configuration['mix_base_relative_url_env_name'])
                        && !empty($spa_configuration['mix_storage_url_env_name'])
                        ) {
                        $MIX_BASE_URL = $spa_configuration['mix_base_url_env_name'];
                        $MIX_BASE_RELATIVE_URL = $spa_configuration['mix_base_relative_url_env_name'];
                        $MIX_STORAGE_URL = $spa_configuration['mix_storage_url_env_name'];
                    }  else {
                        throw new \Exception("no \"mix_base_url_env_name\" or \"mix_base_relative_url_env_name\" or \"mix_storage_url_env_name\"  found in spa", 1);
                    }

                    //set public_relative_path_name
                    if (!empty($spa_configuration['public_folder_name'])) {
                        $public_relative_path_name = $spa_configuration['public_folder_name'];
                    } else {
                        throw new \Exception("public_folder_name must be set with resource_folder_name!!", 1);
                    }
                }

                $resource_public_folder_names []= [
                    'resources_relative_path_name' => $spa_configuration['resource_folder_name'],
                    'public_relative_path_name' => $public_relative_path_name,
                ];

            } else {
                throw new \Exception("no \"resource_folder_name\" found in spa", 1);
            }
        }

        //
        //folder structure creation
        if (!$this->storage_disk->fileExists($this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/infrastructure/app.js')) {
            
            //resources folder 
            $source = __DIR__.'/../../../scaffold_structure/iView/resources';
            $destination = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/.';

            $this->storage_disk->makeDirectory($destination);            
            $this->storage_disk->copyFoldersAndFiles($source, $destination);
            
            //public folder
            $source = __DIR__.'/../../../scaffold_structure/iView/public';
            $destination = $this->storage_disk->getBasePath().'/'.$public_relative_path_name.'/.';
            $this->storage_disk->makeDirectory($destination);

            $this->storage_disk->copyFoldersAndFiles($source, $destination);

        } else {
            throw new \Exception("Already installed!!", 1);
        }


        //
        //write router.js
        $router_full_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/application/router.js';
        
        $stub = $this->storage_disk->readFile(__DIR__.'/../../Stubs/resources/js/router.js.stub');
        $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
        
        $this->storage_disk->writeFile($router_full_path, $stub);


        //
        //write main_menu.js
        $main_menu_full_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/modules/presentation/constants/navigation/main_menu/main_menu.js';
        
        $stub = $this->storage_disk->readFile(__DIR__.'/../../Stubs/resources/js/navigation/main_menu/main_menu.js.stub');
        $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
        
        $this->storage_disk->writeFile($main_menu_full_path, $stub);


        //
        //write demo_sub_menu.js
        $demo_sub_menu_full_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/modules/presentation/constants/navigation/sub_menus/demo_sub_menu.js';
        
        $stub = $this->storage_disk->readFile(__DIR__.'/../../Stubs/resources/js/navigation/sub_menus/demo_sub_menu.js.stub');
        $stub = str_replace('{{MIX_BASE_RELATIVE_URL}}', $MIX_BASE_RELATIVE_URL, $stub);
        
        $this->storage_disk->writeFile($demo_sub_menu_full_path, $stub);
        

        //
        //update env
        $env_path = $this->storage_disk->getBasePath().'/.env';
        
        $lines = $this->storage_disk->readFileArray($env_path); 
        $new_lines = [];
        $add_new_lines = [
            'MIX_BASE_URL' => true,
            'MIX_BASE_RELATIVE_URL' => true,
            'MIX_STORAGE_URL' => true,
        ];
        foreach ($lines as $line) {
            if (strpos($line, strtoupper($MIX_BASE_URL)) === 0) {
                $add_new_lines['MIX_BASE_URL'] = false;
            }
            if (strpos($line, strtoupper($MIX_BASE_RELATIVE_URL)) === 0) {
                $add_new_lines['MIX_BASE_RELATIVE_URL'] = false;
            }
            if (strpos($line, strtoupper($MIX_STORAGE_URL)) === 0) {
                $add_new_lines['MIX_STORAGE_URL'] = false;
            }
        }
        if ($add_new_lines['MIX_BASE_URL'] == true) {
            $new_lines []= "\n";
            $new_lines []= str_replace('{{MIX_BASE_URL}}', strtoupper($MIX_BASE_URL), "\n".'{{MIX_BASE_URL}}=http://localhost/'.$laravel_root_folder_name.'/'.$public_relative_path_name);
        }
        if ($add_new_lines['MIX_BASE_RELATIVE_URL'] == true) {
            $new_lines []= str_replace('{{MIX_BASE_RELATIVE_URL}}', strtoupper($MIX_BASE_RELATIVE_URL), "\n".'{{MIX_BASE_RELATIVE_URL}}=/'.$laravel_root_folder_name.'/'.$public_relative_path_name);
        }
        if ($add_new_lines['MIX_STORAGE_URL'] == true) {
            $new_lines []= str_replace('{{MIX_STORAGE_URL}}', strtoupper($MIX_STORAGE_URL), "\n".'{{MIX_STORAGE_URL}}=/public/storage/app');
        }

        if (!empty($new_lines)) {
            $lines = array_merge($lines, $new_lines);
        }

        $this->storage_disk->writeFileArray($env_path, $lines); 


        //
        //write spa controller
        $name = 'Spa';

        $controller_path = $this->storage_disk->getBasePath().'/app/Http/Controllers/'.ucfirst(strtolower($name)).'Controller.php';
        
        if (!$this->storage_disk->fileExists($controller_path)) {

            $stub = $this->storage_disk->readFile(__DIR__.'/../../Stubs/Crud/Controller.stub');

            $stub = str_replace('{{td_entity}}', strtolower($name), $stub);
            $stub = str_replace('{{resource_folder_name}}', $resources_relative_path_name, $stub);
            $stub = str_replace('{{Td_entity}}', ucfirst(strtolower($name)), $stub);
            
            $this->storage_disk->writeFile($controller_path, $stub);
        }

        //
        //write webpack.mix.js
        $webpack_mix_path = $this->storage_disk->getBasePath().'/webpack.mix.js';        

        $stub = $this->storage_disk->readFile(__DIR__.'/../../Stubs/webpack.mix.js.stub');

        $stub = $this->mustache->render($stub, [
            'laravel_root_folder_name' => $laravel_root_folder_name,
            'webpack_mix_resource_public_folders' => $resource_public_folder_names,
        ]);
        
        $this->storage_disk->writeFile($webpack_mix_path, $stub);


        //
        //write resource routes file
        $Vista_resource_routes_path = $this->storage_disk->getBasePath().'/routes/web.php';
        $resource_routes_file_contents = $this->storage_disk->readFile( $Vista_resource_routes_path );
        $spa_get_urls = '';
        if (strpos($resource_routes_file_contents, 'SpaController@index') === false) {
            $spa_get_urls = "\n".'Route::get(\'/{any}\', \'SpaController@index\')->where(\'any\', \'.*\');';
        }
        $this->storage_disk->writeFile($Vista_resource_routes_path, $spa_get_urls, [
            'append_file' => true
        ]);


        //
        //update store
        $store_path = $this->storage_disk->getBasePath().'/'.$resources_relative_path_name.'/client_app/application/store.js';

        $stub = $this->storage_disk->readFile(__DIR__.'/../../Stubs/resources/js/store.js.stub');

        $stub = $this->mustache->render($stub, [
            'page_modules' => [],
            'browser_local_storage_key' => $browser_local_storage_key
        ]);
        
        $this->storage_disk->writeFile($store_path, $stub);


    }



}