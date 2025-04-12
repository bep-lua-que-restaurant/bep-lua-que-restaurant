<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhieuXuatKhosTable extends Migration
{
    public function up(): void
    {
        Schema::create('phieu_xuat_khos', function (Blueprint $table) {
            $table->id();
            $table->string('ma_phieu')->unique(); // Mã phiếu xuất kho
            $table->date('ngay_xuat'); // Ngày xuất kho

            $table->unsignedBigInteger('nhan_vien_id')->nullable(); // Nhân viên thực hiện
            $table->foreign('nhan_vien_id')->references('id')->on('nhan_viens')->onDelete('set null');

            $table->string('nguoi_nhan')->nullable(); // Người nhận hàng
            $table->enum('loai_phieu', ['xuat_bep', 'xuat_tra_hang', 'xuat_huy'])->default('xuat_bep'); // Loại phiếu xuất kho

            $table->unsignedBigInteger('nha_cung_cap_id')->nullable(); // Nhà cung cấp (nếu trả hàng)
            $table->foreign('nha_cung_cap_id')->references('id')->on('nha_cung_caps')->onDelete('set null');

            $table->decimal('tong_tien', 15, 2)->nullable(); // Tổng tiền của phiếu xuất kho (nếu cần)

            $table->text('ghi_chu')->nullable(); // Ghi chú bổ sung
            $table->enum('trang_thai', ['cho_duyet', 'da_duyet', 'da_huy'])->default('cho_duyet'); // Trạng thái phiếu
            $table->softDeletes(); // Xóa mềm
            $table->timestamps(); // Thời gian tạo và cập nhật
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phieu_xuat_khos');
    }
}