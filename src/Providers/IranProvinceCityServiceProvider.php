<?php

namespace Vendor\IranProvinceCity\Providers;

use Illuminate\Support\ServiceProvider;
use Vendor\IranProvinceCity\Console\Commands\ImportProvincesAndCitiesCommand;

class IranProvinceCityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands([
            ImportProvincesAndCitiesCommand::class,
        ]);
    }

    public function boot(): void
    {
        $basePath = dirname(__DIR__, 2) . '/src/Database';
        $modelPath = dirname(__DIR__, 2) . '/src/Models';

        $this->publishMigrations("{$basePath}/migrations");
        $this->publishSeeders("{$basePath}/seeders");
        $this->publishModels($modelPath);

        $this->loadMigrationsFrom("{$basePath}/migrations");
    }

    protected function publishMigrations(string $migrationPath): void
    {
        $this->publishes([
            "{$migrationPath}/0000_00_00_000001_create_provinces_table.php" => database_path("migrations/0000_00_00_000001_create_provinces_table.php"),
        ], 'iran-province-city-migrations-province');

        $this->publishes([
            "{$migrationPath}/0000_00_00_000002_create_cities_table.php" => database_path("migrations/0000_00_00_000002_create_cities_table.php"),
        ], 'iran-province-city-migrations-city');
    }

    protected function publishSeeders(string $seederPath): void
    {
        $this->publishes([
            "{$seederPath}/ProvinceSeeder.php" => database_path('seeders/ProvinceSeeder.php'),
        ], 'iran-province-city-seeders-province');

        $this->publishes([
            "{$seederPath}/CitySeeder.php" => database_path('seeders/CitySeeder.php'),
        ], 'iran-province-city-seeders-city');
    }

    protected function publishModels(string $modelPath): void
    {
        $this->publishes([
            $modelPath => app_path('Models/IranProvinceCity'),
        ], 'iran-province-city-models');
    }
}
