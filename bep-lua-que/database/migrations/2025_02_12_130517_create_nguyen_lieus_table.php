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
        Schema::create('nguyen_lieus', function (Blueprint $table) {
            $table->id(); // Khóa chính tự động tăng
            $table->string('ma_nguyen_lieu', 50)->unique(); // Mã nguyên liệu duy nhất
            $table->string('ten_nguyen_lieu', 255)->unique(); // Tên nguyên liệu
            $table->foreignId('loai_nguyen_lieu_id')->constrained('loai_nguyen_lieu')->onDelete('set null'); // FK đến bảng loai_nguyen_lieu
            $table->string('don_vi_tinh', 50); // Đơn vị tính
            $table->decimal('so_luong_ton', 15, 2)->default(0); // Số lượng tồn kho
            $table->decimal('gia_nhap', 15, 2)->default(0); // Giá nhập nguyên liệu
            $table->string('hinh_anh', 255)->nullable(); // Hình ảnh của nguyên liệu
            $table->text('mo_ta')->nullable(); // Mô tả nguyên liệu
            $table->softDeletes(); // Xóa mềm
            $table->timestamps(); // Thời gian tạo và cập nhật
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguyen_lieus');
    }
};
