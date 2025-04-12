<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhieuNhapKhosTable extends Migration
{
    public function up(): void
    {
        Schema::create('phieu_nhap_khos', function (Blueprint $table) {
            $table->id();
            $table->string('ma_phieu')->unique();
            $table->date('ngay_nhap');

            $table->unsignedBigInteger('nha_cung_cap_id')->nullable();
            $table->foreign('nha_cung_cap_id')->references('id')->on('nha_cung_caps')->onDelete('set null');

            $table->unsignedBigInteger('nhan_vien_id')->nullable(); // Thêm trường nhân viên
            $table->foreign('nhan_vien_id')->references('id')->on('nhan_viens')->onDelete('set null');
            $table->decimal('tong_tien', 15, 2)->default(0);
            $table->text('ghi_chu')->nullable();
            $table->enum('trang_thai', ['cho_duyet', 'da_duyet', 'da_huy'])->default('cho_duyet');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phieu_nhap_khos');
    }
}