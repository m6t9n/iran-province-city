<?php

use Vendor\IranProvinceCity\Models\Province;
use Vendor\IranProvinceCity\Models\City;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     ایجاد جداول شهر و استان به ترتیب وابستگی *
     */
    public function up(): void
    {
        Schema::create(Province::TABLE, function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger(Province::CODE)
                ->unique()
                ->comment('کد استان');

            $table->string(Province::NAME)
                ->comment('نام استان');

            $table->timestamps();
        });

        Schema::create(City::TABLE, function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger(City::CODE)
                ->unique()
                ->index()
                ->comment('کد شهر');

            $table->string(City::NAME)
                ->comment('نام شهر');

            $table->unsignedBigInteger(City::PROVINCE_CODE)
                ->index()
                ->comment('کد استان مرتبط');

            $table->double(City::LATITUDE)
                ->nullable()
                ->comment('عرض جغرافیایی');

            $table->double(City::LONGITUDE)
                ->nullable()
                ->comment('طول جغرافیایی');

            $table->timestamps();

            $table->foreign(City::PROVINCE_CODE)
                ->references(Province::CODE)
                ->on(Province::TABLE)
                ->cascadeOnDelete();
        });
    }

    /**
     حذف جداول شهر و استان به ترتیب وابستگی *
     */
    public function down(): void
    {
        Schema::table(City::TABLE, function (Blueprint $table) {
            $table->dropForeign([City::PROVINCE_CODE]);
        });

        Schema::dropIfExists(City::TABLE);
        Schema::dropIfExists(Province::TABLE);
    }
};
