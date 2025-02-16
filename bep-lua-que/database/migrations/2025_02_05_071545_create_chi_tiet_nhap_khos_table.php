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
        Schema::create('chi_tiet_nhap_khos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phieu_nhap_kho_id');
            $table->unsignedBigInteger('nguyen_lieu_id');
            $table->unsignedBigInteger('loai_nguyen_lieu_id')->nullable();
            $table->unsignedBigInteger('kho_id');
            $table->decimal('so_luong', 15, 2);
            $table->decimal('gia_nhap', 15, 2);
            $table->decimal('thanh_tien', 15, 2);
            $table->boolean('da_nhap_kho')->default(false); //false-> Nguyên liệu chưa nhập vào kho
            $table->softDeletes();
            $table->timestamps();
        
            // Khóa ngoại
            $table->foreign('phieu_nhap_kho_id')->references('id')->on('phieu_nhap_khos')->onDelete('cascade');
            $table->foreign('nguyen_lieu_id')->references('id')->on('nguyen_lieus')->onDelete('cascade');
            $table->foreign('loai_nguyen_lieu_id')->references('id')->on('loai_nguyen_lieus')->onDelete('set null');
            $table->foreign('kho_id')->references('id')->on('khos')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_nhap_khos');
    }
};
