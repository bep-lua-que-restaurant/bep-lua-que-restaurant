<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('phieu_nhap_khos');
    }

    public function down(): void
    {
        Schema::create('phieu_nhap_khos', function ($table) {
            $table->bigIncrements('id');
            $table->string('ma_phieu_nhap', 255);
            $table->bigInteger('nhan_vien_id');
            $table->bigInteger('nha_cung_cap_id');
            $table->date('ngay_nhap');
            $table->text('ghi_chu')->nullable();
            $table->enum('trang_thai', ['cho_duyet', 'da_duyet', 'hoan_thanh', 'huy']);
            $table->softDeletes();
            $table->timestamps();
        });
    }
};

