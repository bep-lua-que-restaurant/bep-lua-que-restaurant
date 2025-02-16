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
        Schema::create('phieu_nhap_khos', function (Blueprint $table) {
            $table->id(); // Khóa chính tự động tăng
            $table->string('ma_phieu_nhap', 50)->unique(); // Mã phiếu nhập duy nhất
            $table->unsignedBigInteger('nhan_vien_id'); // FK đến bảng nhan_vien
            $table->unsignedBigInteger('nha_cung_cap_id'); // FK đến bảng nha_cung_cap
            $table->dateTime('ngay_nhap'); // Ngày nhập kho
            $table->text('ghi_chu')->nullable(); // Ghi chú phiếu nhập
            $table->softDeletes(); // Xóa mềm
            $table->timestamps(); // Timestamps (created_at & updated_at)
        
            // Định nghĩa khóa ngoại
            $table->foreign('nhan_vien_id')->references('id')->on('nhan_viens')->onDelete('cascade');
            $table->foreign('nha_cung_cap_id')->references('id')->on('nha_cung_caps')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phieu_nhap_khos');
    }
};
