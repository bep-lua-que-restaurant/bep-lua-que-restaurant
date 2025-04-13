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
        Schema::create('xuat_khos', function (Blueprint $table) {
            $table->id();
            $table->string('ma_xuat_kho')->unique();
            $table->unsignedBigInteger('nhan_vien_id');
            $table->unsignedBigInteger('nguyen_lieu_id');
            $table->unsignedBigInteger('kho_id');
            $table->decimal('so_luong', 15, 2);
            $table->date('ngay_xuat');
            $table->enum('trang_thai', ['da_xuat', 'chua_xuat'])->default('chua_xuat');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xuat_khos');
    }
};
