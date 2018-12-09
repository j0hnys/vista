<?php

namespace j0hnys\Vista\Builders\Crud;

class CrudBuilder
{
    
    /**
     * Crud constructor.
     * @param string $name
     * @throws \Exception
     */
    public function __construct($name = 'TEST')
    {
        
        // $name = 'Spa';

        // //
        // //controller generation
        // $controller_path = base_path().'/app/Http/Controllers/'.ucfirst(strtolower($name)).'Controller.php';
        
        // if (!file_exists($controller_path)) {
        //     $this->makeDirectory($controller_path);

        //     $stub = file_get_contents(__DIR__.'/../../Stubs/Crud/Controller.stub');

        //     $stub = str_replace('{{td_entity}}', strtolower($name), $stub);
        //     $stub = str_replace('{{Td_entity}}', ucfirst(strtolower($name)), $stub);
            
        //     file_put_contents($controller_path, $stub);
        // }


        // //
        // //update resource routes
        // $Td_entities_workflows = $this->getCurrentControllers();
        
        // $workflows = array_map(function($element){
        //     return [
        //         'Td_entity' => $element,
        //         'td_entity' => strtolower($element),
        //     ];
        // },$Td_entities_workflows);

        // $Vista_resource_routes_path = base_path().'/routes/Vista.php';
        // $stub = file_get_contents(__DIR__.'/../../Stubs/routes/Vista.stub');
        // $stub = $mustache->render($stub, [
        //     'register_resource_routes' => $workflows,
        // ]);

        // file_put_contents($Vista_resource_routes_path, $stub);

        

            
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
    public function getCurrentControllers()
    {
        $files = scandir(base_path().'/app/Http/Controllers/Vista/');

        $filenames = [];
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $filenames []= str_replace('Controller.php','',$file);
            }
        }

        return $filenames;
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