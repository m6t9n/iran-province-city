<?php

use App\Models\Province;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     ایجاد جدول استان *
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
    }

    /**
     حذف جدول استان *
     */
    public function down(): void
    {
        Schema::dropIfExists(Province::TABLE);
    }
};
