<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nguyen_lieus', function (Blueprint $table) {
            $table->dropColumn(['so_luong_ton_toi_thieu', 'so_luong_ton_toi_da']); // Xóa 2 cột
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguyen_lieus', function (Blueprint $table) {
            $table->integer('so_luong_ton_toi_thieu')->nullable(); // Thêm lại cột nếu rollback
            $table->integer('so_luong_ton_toi_da')->nullable();
        });
    }
};
