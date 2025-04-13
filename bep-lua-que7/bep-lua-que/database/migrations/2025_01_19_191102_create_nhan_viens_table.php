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
        Schema::create('nhan_viens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chuc_vu_id');
            $table->string('ma_nhan_vien')->unique();
            $table->string('ho_ten');
            $table->string('email')->unique();
            $table->string('so_dien_thoai')->unique();
            $table->string('dia_chi')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->enum('gioi_tinh', ['nam', 'nu'])->default('nam');
            $table->date('ngay_sinh')->nullable();
            $table->date('ngay_vao_lam')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhan_viens');
    }
};
