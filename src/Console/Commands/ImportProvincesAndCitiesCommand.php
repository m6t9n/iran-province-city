<?php

namespace Vendor\IranProvinceCity\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ImportProvincesAndCitiesCommand extends Command
{
    protected $signature = 'iran-province-city:all';

    protected $description = 'انتشار فایل‌های مایگریشن و سیدر، اجرای مایگریشن‌ها و وارد کردن داده‌های استان‌ها و شهرها';

    public function handle()
    {
        $this->info('در حال انتشار فایل‌های مایگریشن...');
        try {
            $this->callSilent('vendor:publish', [
                '--provider' => 'Vendor\IranProvinceCity\Providers\YourPackageServiceProvider',
                '--tag' => 'iran-province-city-migrations',
                '--force' => true,
            ]);
            $this->info('فایل‌های مایگریشن با موفقیت منتشر شدند.');
        } catch (Exception $e) {
            $this->error('خطا در انتشار فایل‌های مایگریشن: ' . $e->getMessage());
            return 1;
        }

        $this->info('در حال انتشار فایل‌های سیدر...');
        try {
            $this->callSilent('vendor:publish', [
                '--provider' => 'Vendor\IranProvinceCity\Providers\YourPackageServiceProvider',
                '--tag' => 'iran-province-city-seeders',
                '--force' => true,
            ]);
            $this->info('فایل‌های سیدر با موفقیت منتشر شدند.');
        } catch (Exception $e) {
            $this->error('خطا در انتشار فایل‌های سیدر: ' . $e->getMessage());
            return 1;
        }

        $this->info('در حال اجرای مایگریشن‌ها...');
        try {
            Artisan::call('migrate', [
                '--force' => true,
            ]);
            $this->info(Artisan::output());
        } catch (Exception $e) {
            $this->error('اجرای مایگریشن‌ها با خطا مواجه شد: ' . $e->getMessage());
            return 1;
        }

        $this->info('در حال اجرای سیدرها...');
        try {
            Artisan::call('db:seed', [
                '--class' => 'Vendor\\IranProvinceCity\\Database\\Seeders\\ProvinceCitySeeder',
                '--force' => true,
            ]);
            $this->info(Artisan::output());
        } catch (Exception $e) {
            $this->error('اجرای سیدرها با خطا مواجه شد: ' . $e->getMessage());
            return 1;
        }

        $this->info('عملیات با موفقیت انجام شد.');
        return 0;
    }
}
