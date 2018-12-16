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
        if (!empty($resources_relative_path_name_)) {
            $resources_relative_path_name = $resources_relative_path_name_;
        }

        //
        //folder structure creation
        if (!file_exists(base_path().'/'.$resources_relative_path_name.'/js/app.js')) {
            
            $source = __DIR__.'/../../../scaffold_structure/resources';
            $destination = base_path().'/'.$resources_relative_path_name.'';
            
            $this->copyFoldersAndFiles($source, $destination);
        } else {
            throw new \Exception("Already installed!!", 1);
        }
        

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