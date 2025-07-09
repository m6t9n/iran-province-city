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
        $migrationPath = __DIR__ . '/../../src/Database/migrations';
        $seederPath = __DIR__ . '/../../src/Database/Seeders';
        $modelPath = __DIR__ . '/../../src/Models';

        $this->publishes([
            $migrationPath => database_path('migrations'),
        ], 'iran-province-city-migrations');

        $this->publishes([
            $seederPath => database_path('seeders'),
        ], 'iran-province-city-seeders');

        $this->publishes([
            $modelPath => app_path('Models/IranProvinceCity'),
        ], 'iran-province-city-models');

        $this->loadMigrationsFrom($migrationPath);
    }
}
