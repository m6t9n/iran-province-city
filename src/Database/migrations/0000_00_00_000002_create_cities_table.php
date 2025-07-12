<?php

use Models\Province;
use Models\City;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     ایجاد جدول شهر *
     */
    public function up(): void
    {
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
     حذف جدول شهر *
     */
    public function down(): void
    {
        Schema::table(City::TABLE, function (Blueprint $table) {
            $table->dropForeign([City::PROVINCE_CODE]);
        });

        Schema::dropIfExists(City::TABLE);
    }
};
