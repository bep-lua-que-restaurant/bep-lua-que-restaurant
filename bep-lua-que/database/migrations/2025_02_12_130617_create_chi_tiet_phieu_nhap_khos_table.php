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
        Schema::create('chi_tiet_phieu_nhap_khos', function (Blueprint $table) {
            $table->id(); // Khóa chính tự động tăng
            $table->foreignId('phieu_nhap_kho_id') // FK đến bảng phieu_nhap_khos
                  ->constrained('phieu_nhap_khos') // Tên bảng tham chiếu
                  ->onDelete('cascade'); // Xóa dữ liệu khi bản ghi cha bị xóa
            $table->foreignId('nguyen_lieu_id') // FK đến bảng nguyen_lieus
                  ->constrained('nguyen_lieus') // Tên bảng tham chiếu
                  ->onDelete('cascade'); // Xóa dữ liệu khi bản ghi cha bị xóa
            $table->integer('so_luong')->unsigned(); // Số lượng nguyên liệu nhập
            $table->decimal('don_gia', 15, 2); // Giá nhập nguyên liệu
            $table->decimal('tong_tien', 15, 2)->nullable(); // Tổng tiền tính từ số lượng và giá nhập
            $table->date('han_su_dung')->nullable(); // Hạn sử dụng (nếu có)
            $table->enum('trang_thai', ['Đạt', 'Không đạt', 'Cần kiểm tra'])->default('Đạt'); // Trạng thái nguyên liệu
            $table->softDeletes(); // Xóa mềm
            $table->timestamps(); // Timestamps (created_at & updated_at)
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_phieu_nhap_khos');
    }
};
