<?php

namespace Vendor\IranProvinceCity\Database\Seeders;

use App\Models\IranProvinceCity\Province;
use Illuminate\Database\Seeder;
use Exception;

class ProvinceSeeder extends Seeder
{
    protected string $dataPath;

    public function __construct()
    {
        $this->dataPath = dirname(__DIR__, 2) . '/data';
    }

    /**
     * ایجاد یا بروزرسانی استان‌ها
     *
     * @return void
     */
    public function run(): void
    {
        $provinces = require $this->dataPath . '/provinces.php';

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

        $this->command->info('✅ ایجاد یا به‌روزرسانی استان‌ها با موفقیت به پایان رسید.');
    }
}
