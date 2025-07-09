<?php

namespace Vendor\IranProvinceCity\Database\Seeders;

use Illuminate\Database\Seeder;
use Vendor\IranProvinceCity\Models\City;
use Vendor\IranProvinceCity\Models\Province;
use Exception;

class ProvinceCitySeeder extends Seeder
{
    /**
     ایجاد شهر ها و استان ها *
     *
     * @return void
     */
    public function run(): void
    {
        $provinces = require __DIR__ . '/../../data/provinces.php';
        $cities = require __DIR__ . '/../../data/cities.php';

        foreach ($provinces as $province) {
            try {
                Province::query()->updateOrCreate(
                    [Province::CODE => $province['code']],
                    [Province::NAME => $province['name']]
                );
            } catch (Exception $e) {
                $this->command->error("خطا در ایجاد یا به‌روزرسانی استان «{$province['name']}»: " . $e->getMessage());
            }
        }

        foreach ($cities as $city) {
            try {
                City::query()->updateOrCreate(
                    [City::CODE => $city['code']],
                    [
                        City::NAME => $city['name'],
                        City::PROVINCE_CODE => $city['province_code'],
                        City::LATITUDE => $city['latitude'],
                        City::LONGITUDE => $city['longitude'],
                    ]
                );
            } catch (Exception $e) {
                $this->command->error("خطا در ایجاد یا به‌روزرسانی شهر «{$city['name']}»: " . $e->getMessage());
            }
        }
        $this->command->info('ایجاد یا به‌روزرسانی استان‌ها و شهرها با موفقیت به پایان رسید.');
    }
}
