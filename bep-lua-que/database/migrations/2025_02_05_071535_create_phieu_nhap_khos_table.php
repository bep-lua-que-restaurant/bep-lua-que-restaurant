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
            $table->id();
            $table->string('ma_phieu_nhap')->unique();
            $table->unsignedBigInteger('nhan_vien_id');
            $table->unsignedBigInteger('nha_cung_cap_id')->nullable();
            $table->date('ngay_nhap');
            $table->decimal('tong_tien', 15, 2)->default(0);
            $table->enum('trang_thai', ['cho_duyet', 'da_duyet', 'hoan_thanh', 'huy'])->default('cho_duyet')->change();
            $table->softDeletes();
            $table->timestamps();
        
            // Khóa ngoại
            $table->foreign('nhan_vien_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('nha_cung_cap_id')->references('id')->on('nha_cung_caps')->onDelete('set null');
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
