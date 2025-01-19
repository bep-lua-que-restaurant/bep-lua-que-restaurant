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
        Schema::create('nguyen_lieus', function (Blueprint $table) {
            $table->id();
            $table->string('ma_nguyen_lieu')->unique();
            $table->string('ten_nguyen_lieu', 255);
            $table->string('don_vi_tinh', 50);
            $table->decimal('so_luong_ton', 15, 2)->default(0);
            $table->decimal('so_luong_ton_toi_thieu', 15, 2)->default(0);
            $table->decimal('so_luong_ton_toi_da', 15, 2)->default(0);
            $table->decimal('gia_nhap', 15, 2)->default(0);
            $table->string('hinh_anh', 255)->nullable();
            $table->text('mo_ta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguyen_lieus');
    }
};
