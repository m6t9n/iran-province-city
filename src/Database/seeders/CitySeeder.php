<?php

namespace Vendor\IranProvinceCity\Database\Seeders;

use Vendor\IranProvinceCity\app\Models\City;
use Vendor\IranProvinceCity\app\Models\Province;
use Illuminate\Database\Seeder;
use Exception;

class CitySeeder extends Seeder
{
    protected string $dataPath;

    public function __construct()
    {
        $this->dataPath = dirname(__DIR__, 2) . '/Data';
    }

    /**
     * ایجاد یا به‌روزرسانی شهرها
     *
     * @return void
     */
    public function run(): void
    {
        $cities = require $this->dataPath . '/Cities.php';

        foreach ($cities as $city) {
            try {
                City::query()->updateOrCreate(
                    [City::CODE => $city['code']],
                    [
                        City::NAME => $city['name'],
                        City::PROVINCE_CODE => $city['province_code'],
                        City::LATITUDE => $city['latitude'] ?? null,
                        City::LONGITUDE => $city['longitude'] ?? null,
                    ]
                );
            } catch (Exception $e) {
                $this->command->error("خطا در ایجاد یا به‌روزرسانی شهر «{$city['name']}»: " . $e->getMessage());
            }
        }

        $this->command->info('✅ ایجاد یا به‌روزرسانی شهرها با موفقیت به پایان رسید.');
    }
}
