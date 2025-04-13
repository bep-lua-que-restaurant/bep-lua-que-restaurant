<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChiTietPhieuXuatKhosTable extends Migration
{
    public function up(): void
    {
        Schema::create('chi_tiet_phieu_xuat_khos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('phieu_xuat_kho_id')->constrained('phieu_xuat_khos')->onDelete('cascade'); // Liên kết đến phiếu xuất kho
            $table->foreignId('nguyen_lieu_id')->constrained('nguyen_lieus')->onDelete('cascade'); // Liên kết đến nguyên liệu

            $table->string('don_vi_xuat'); // Đơn vị xuất (ví dụ: kg, thùng, chai,...)
            $table->float('he_so_quy_doi', 10, 4)->nullable(); // Hệ số quy đổi từ đơn vị xuất sang đơn vị tồn kho
            $table->float('so_luong', 10, 2)->check('so_luong > 0'); // Số lượng xuất theo đơn vị xuất
            $table->float('don_gia', 15, 2)->nullable()->check('don_gia >= 0'); // Giá của 1 đơn vị xuất (nếu cần)

            $table->text('ghi_chu')->nullable(); // Ghi chú bổ sung

            $table->softDeletes(); // Xóa mềm
            $table->timestamps(); // Thời gian tạo và cập nhật
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_phieu_xuat_khos');
    }
}