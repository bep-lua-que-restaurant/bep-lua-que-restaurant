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
        Schema::create('ma_giam_gias', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã giảm giá
            $table->enum('type', ['percentage', 'fixed']); // Loại giảm giá: phần trăm hoặc số tiền
            $table->decimal('value', 8, 2); // Giá trị giảm
            $table->decimal('min_order_value', 8, 2)->nullable(); // Đơn hàng tối thiểu (nếu có)
            $table->dateTime('start_date'); // Ngày bắt đầu hiệu lực
            $table->dateTime('end_date'); // Ngày kết thúc hiệu lực
            $table->integer('usage_limit')->default(0); // Giới hạn lượt sử dụng (0: không giới hạn)
            $table->integer('used')->default(0); // Số lượt đã sử dụng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ma_giam_gias');
    }
};
