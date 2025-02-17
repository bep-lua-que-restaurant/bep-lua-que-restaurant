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
        Schema::create('nha_cung_caps', function (Blueprint $table) {
            $table->id(); // Khóa chính tự động tăng
            $table->string('ma_nha_cung_cap', 50)->unique(); // Mã nhà cung cấp duy nhất
            $table->string('ten_nha_cung_cap', 255); // Tên nhà cung cấp
            $table->string('dia_chi', 255)->nullable(); // Địa chỉ
            $table->string('so_dien_thoai', 20)->unique(); // Số điện thoại
            $table->string('email', 255)->unique(); // Email nhà cung cấp
            $table->text('ghi_chu')->nullable(); // Ghi chú nhà cung cấp
            $table->softDeletes(); // Xóa mềm
            $table->timestamps(); // Timestamps (created_at & updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nha_cung_caps');
    }
};
