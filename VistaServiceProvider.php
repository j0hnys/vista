<?php

namespace j0hnys\Vista;

use Illuminate\Support\ServiceProvider;
use j0hnys\Vista\Console\Commands\GenerateCrud;
use j0hnys\Vista\Console\Commands\Install;
use j0hnys\Vista\Console\Commands\ExportModel;
// . . .

class VistaServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__.'/config/vista.php';
        $this->publishes([
            $configPath => config_path('vista.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('vista.generate_crud', function ($app) {
            return new GenerateCrud();
        });
        $this->app->singleton('vista.install', function ($app) {
            return new Install();
        });
        $this->app->singleton('vista.export_model', function ($app) {
            return new ExportModel();
        });
        // . . .

        $this->commands([
            'vista.generate_crud',
            'vista.install',
            'vista.export_model',
            // . . .
        ]);
    }

}