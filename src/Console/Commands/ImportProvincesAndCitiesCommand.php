<?php

namespace Vendor\IranProvinceCity\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class ImportProvincesAndCitiesCommand extends Command
{
    protected string $signature = 'iran-province-city:interactive';

    protected string $description = 'انتشار و اجرای مایگریشن و سیدر استان و شهر به صورت تعاملی';

    public function handle()
    {
        $migrationChoice = $this->choice(
            'کدام مایگریشن را می‌خواهید منتشر و اجرا کنید؟',
            ['province', 'city', 'province & city'],
            default: 'province & city'
        );

        if (! $this->handleMigrations($migrationChoice)) {
            return Command::FAILURE;
        }

        if ($this->confirm('آیا مایل به اجرای سیدر هستید؟', true)) {
            $seederChoice = $this->choice(
                'کدام سیدر را می‌خواهید اجرا کنید؟',
                ['province', 'city', 'province & city'],
                default: 'province & city'
            );

            if (! $this->handleSeeders($seederChoice)) {
                return Command::FAILURE;
            }
        }

        $this->newLine();
        $this->info('✅ عملیات با موفقیت به پایان رسید.');
        return Command::SUCCESS;
    }

    protected function handleMigrations(string $choice): bool
    {
        $this->section('در حال انتشار فایل‌های مایگریشن...');

        try {
            if (in_array($choice, ['province', 'province & city'])) {
                $this->publish('iran-province-city-migrations-province');
            }

            if (in_array($choice, ['city', 'province & city'])) {
                $this->publish('iran-province-city-migrations-city');
            }

            $this->info('✔ فایل‌های مایگریشن با موفقیت منتشر شدند.');
            $this->section('در حال اجرای migrate...');
            Artisan::call('migrate', ['--force' => true]);
            $this->line(Artisan::output());

            return true;
        } catch (Throwable $e) {
            $this->error('❌ خطا در انتشار یا اجرای مایگریشن‌ها: ' . $e->getMessage());
            return false;
        }
    }

    protected function handleSeeders(string $choice): bool
    {
        $this->section('در حال اجرای سیدرها...');

        try {
            if (in_array($choice, ['province', 'province & city'])) {
                $this->runSeeder('Vendor\\IranProvinceCity\\Database\\Seeders\\ProvinceSeeder');
            }

            if (in_array($choice, ['city', 'province & city'])) {
                $this->runSeeder('Vendor\\IranProvinceCity\\Database\\Seeders\\CitySeeder');
            }

            return true;
        } catch (Throwable $e) {
            $this->error('❌ خطا در اجرای سیدرها: ' . $e->getMessage());
            return false;
        }
    }

    protected function publish(string $tag): void
    {
        $this->callSilent('vendor:publish', [
            '--provider' => 'Vendor\IranProvinceCity\Providers\IranProvinceCityServiceProvider',
            '--tag' => $tag,
            '--force' => true,
        ]);
    }

    protected function runSeeder(string $seederClass): void
    {
        Artisan::call('db:seed', [
            '--class' => $seederClass,
            '--force' => true,
        ]);
        $this->line(Artisan::output());
    }

    protected function section(string $title): void
    {
        $this->newLine();
        $this->info('── ' . $title);
    }
}
