<?php

namespace Vendor\IranProvinceCity\Providers;

use Illuminate\Support\ServiceProvider;
use Vendor\IranProvinceCity\Console\Commands\ImportProvincesAndCitiesCommand;

class IranProvinceCityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            ImportProvincesAndCitiesCommand::class,
        ]);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../src/Database/migrations/' => database_path('migrations'),
        ], 'iran-province-city-migrations');

        $this->publishes([
            __DIR__ . '/../../src/Database/Seeders/' => database_path('seeders'),
        ], 'iran-province-city-seeders');

        $this->publishes([
            __DIR__ . '/../../src/Models/' => app_path('Models/IranProvinceCity'),
        ], 'iran-province-city-models');

        $this->loadMigrationsFrom(__DIR__ . '/../../src/Database/migrations');
    }

}
