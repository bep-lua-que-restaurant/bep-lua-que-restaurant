<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('chi_tiet_phieu_nhap_khos');
    }

    public function down(): void
    {
        // Nếu muốn rollback có thể tạo lại bảng (tuỳ chọn)
        Schema::create('chi_tiet_phieu_nhap_khos', function ($table) {
            $table->bigIncrements('id');
            $table->bigInteger('phieu_nhap_kho_id');
            $table->bigInteger('nguyen_lieu_id');
            $table->integer('so_luong');
            $table->string('don_vi_nhap', 50);
            $table->decimal('so_luong_quy_doi', 15, 2);
            $table->decimal('don_gia', 15, 2);
            $table->decimal('tong_tien', 15, 2);
            $table->date('han_su_dung');
            $table->enum('trang_thai', ['Đạt', 'Không đạt', 'Cần kiểm tra']);
            $table->softDeletes();
            $table->timestamps();
        });
    }
};

