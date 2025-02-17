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
        Schema::create('loai_nguyen_lieus', function (Blueprint $table) {
            $table->id(); // Khóa chính tự động tăng
            $table->string('ma_loai', 50)->unique(); // Mã loại nguyên liệu duy nhất
            $table->string('ten_loai', 255)->unique(); // Tên loại nguyên liệu
            $table->text('mo_ta')->nullable(); // Mô tả loại nguyên liệu
            $table->softDeletes(); // Thêm xóa mềm
            $table->timestamps(); // Timestamps (created_at & updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loai_nguyen_lieus');
    }
};
