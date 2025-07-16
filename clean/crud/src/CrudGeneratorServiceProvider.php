<?php

namespace Onzup\Crud;

use Illuminate\Support\ServiceProvider;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/crudgenerator.php' => config_path('crudgenerator.php'),
        ]);

        // $this->publishes([
        //     __DIR__ . '/stubs/' => base_path('resources/crud-generator/'),
        // ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(
            'Onzup\Crud\Commands\CrudCommand',
            'Onzup\Crud\Commands\CrudControllerCommand',
            'Onzup\Crud\Commands\CrudModelCommand',
            'Onzup\Crud\Commands\CrudMigrationCommand',
            'Onzup\Crud\Commands\CrudViewCommand',
            'Onzup\Crud\Commands\CrudDataTableCommand'
        );
    }

}
