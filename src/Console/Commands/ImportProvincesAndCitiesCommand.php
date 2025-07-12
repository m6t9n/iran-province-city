<?php

namespace Vendor\IranProvinceCity\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
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

        $this->info('✅ All operations completed successfully. Package by — https://github.com/m6t9n');

        return $migrationSuccess && $seederSuccess ? Command::SUCCESS : Command::FAILURE;
    }

    protected function handleMigrations(string $choice): bool
    {
        $this->section('Publishing migration files and models...');

        $allSuccess = true;

        $map = [
            'province' => [
                'tag' => 'iran-province-city-migrations-province',
                'modelPath' => dirname(__DIR__, 3) . '/Models/Province/Province.php',
                'target' => app_path('Models/Province.php'),
            ],
            'city' => [
                'tag' => 'iran-province-city-migrations-city',
                'modelPath' => dirname(__DIR__, 3) . '/Models/City/City.php',
                'target' => app_path('Models/City.php'),
            ],
        ];

        $choices = match ($choice) {
            'province' => ['province'],
            'city' => ['city'],
            default => ['province', 'city'],
        };

        foreach ($choices as $key) {
            try {
                $this->callSilent('vendor:publish', [
                    '--provider' => 'Vendor\IranProvinceCity\Providers\IranProvinceCityServiceProvider',
                    '--tag' => $map[$key]['tag'],
                    '--force' => true,
                ]);

                if (!is_dir(dirname($map[$key]['target']))) {
                    mkdir(dirname($map[$key]['target']), 0755, true);
                }

                if (file_exists($map[$key]['target'])) {
                    $this->line("⚠ Model file already exists: {$map[$key]['target']}");
                } else {
                    copy($map[$key]['modelPath'], $map[$key]['target']);
                }
            } catch (Throwable) {
                $allSuccess = false;
            }
        }

        $this->info($allSuccess ? '✔ Migrations and models published.' : '❌ Some migrations or models failed to publish.');

        $this->section('Running migrate...');

        $tablesToCheck = match ($choice) {
            'province' => ['provinces'],
            'city' => ['cities'],
            default => ['provinces', 'cities'],
        };

        $alreadyExists = collect($tablesToCheck)
            ->filter(fn($table) => Schema::hasTable($table))
            ->all();

        if (!empty($alreadyExists)) {
            $this->warn('⚠ The following tables already exist and migrations will be skipped: ' . implode(', ', $alreadyExists));
            $this->info('✔ Migration executed.');
            return $allSuccess;
        }

        $exitCode = Artisan::call('migrate', ['--force' => true]);
        $this->line(Artisan::output());

        $this->info('✔ Migration executed.');

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
            'province' => ['Vendor\\IranProvinceCity\\Database\\seeders\\ProvinceSeeder'],
            'city' => ['Vendor\\IranProvinceCity\\Database\\seeders\\CitySeeder'],
            default => [
                'Vendor\\IranProvinceCity\\Database\\seeders\\ProvinceSeeder',
                'Vendor\\IranProvinceCity\\Database\\seeders\\CitySeeder',
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

        $this->info('✔ Seeders executed successfully.');

        return $allSuccess;
    }

    protected function section(string $title): void
    {
        $this->newLine();
        $this->info('── ' . $title);
    }
}
