<?php

namespace Vendor\IranProvinceCity\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class ImportProvincesAndCitiesCommand extends Command
{
    protected $signature = 'iran-province-city:interactive';
    protected $description = 'Publish and run province and city migrations, seeders and models interactively';

    public function handle(): int
    {
        $migrationChoice = $this->choice(
            'Which migrations do you want to publish and run?',
            ['province & city', 'province', 'city'],
            default: 'province & city'
        );

        $migrationSuccess = $this->handleMigrations($migrationChoice);

        $seederSuccess = true;
        if ($this->confirm('Do you want to run seeders?', true)) {
            $seederChoice = $this->choice(
                'Which seeder do you want to run?',
                ['province & city', 'province', 'city'],
                default: 'province & city'
            );

            $seederSuccess = $this->handleSeeders($seederChoice);
        }

        $this->newLine();

        if ($migrationSuccess && $seederSuccess) {
            $this->info('✅ Operation completed successfully.');
        } else {
            $this->error('❌ Operation completed with some errors.');
        }

        $this->line("You can now close this terminal.");
        return $migrationSuccess && $seederSuccess ? Command::SUCCESS : Command::FAILURE;
    }

    protected function handleMigrations(string $choice): bool
    {
        $this->section('Publishing migration files and models...');

        $allSuccess = true;

        // Migration + model tag mapping
        $map = [
            'province' => [
                'tag' => 'iran-province-city-migrations-province',
                'modelPath' => dirname(__DIR__, 3) . '/Models/Province/Province.php',
                'target' => app_path('Models/IranProvinceCity/Province.php'),
            ],
            'city' => [
                'tag' => 'iran-province-city-migrations-city',
                'modelPath' => dirname(__DIR__, 3) . '/Models/City/City.php',
                'target' => app_path('Models/IranProvinceCity/City.php'),
            ],
        ];

        $choices = match ($choice) {
            'province' => ['province'],
            'city' => ['city'],
            default => ['province', 'city'],
        };

        foreach ($choices as $key) {
            try {
                // Publish migration tag
                $this->callSilent('vendor:publish', [
                    '--provider' => 'Vendor\IranProvinceCity\Providers\IranProvinceCityServiceProvider',
                    '--tag' => $map[$key]['tag'],
                    '--force' => true,
                ]);

                // Manually publish model (single file)
                if (!is_dir(dirname($map[$key]['target']))) {
                    mkdir(dirname($map[$key]['target']), 0755, true);
                }

                copy($map[$key]['modelPath'], $map[$key]['target']);
            } catch (Throwable) {
                $allSuccess = false;
            }
        }

        $this->info($allSuccess ? '✔ Migrations and models published.' : '❌ Some migrations or models failed to publish.');

        $this->section('Running migrate...');
        $exitCode = Artisan::call('migrate', ['--force' => true]);
        $this->line(Artisan::output());

        if ($exitCode !== 0) {
            $this->error('❌ Migration execution failed.');
            return false;
        }

        return $allSuccess;
    }

    protected function handleSeeders(string $choice): bool
    {
        $this->section('Running seeders...');

        $seeders = match ($choice) {
            'province' => ['Vendor\\IranProvinceCity\\Database\\Seeders\\ProvinceSeeder'],
            'city' => ['Vendor\\IranProvinceCity\\Database\\Seeders\\CitySeeder'],
            default => [
                'Vendor\\IranProvinceCity\\Database\\Seeders\\ProvinceSeeder',
                'Vendor\\IranProvinceCity\\Database\\Seeders\\CitySeeder',
            ],
        };

        $allSuccess = true;

        foreach ($seeders as $seeder) {
            $exitCode = Artisan::call('db:seed', [
                '--class' => $seeder,
                '--force' => true,
            ]);

            if ($exitCode !== 0) {
                $allSuccess = false;
            }
        }

        $this->info($allSuccess ? '✔ Seeders executed successfully.' : '❌ Some seeders failed.');
        return $allSuccess;
    }

    protected function section(string $title): void
    {
        $this->newLine();
        $this->info('── ' . $title);
    }
}
