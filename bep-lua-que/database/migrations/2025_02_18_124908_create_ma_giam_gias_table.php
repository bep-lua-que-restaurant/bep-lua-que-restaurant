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
            $table->integer('discount'); // Số tiền giảm hoặc phần trăm giảm
            $table->enum('type', ['percent', 'fixed']); // Kiểu giảm giá
            $table->integer('quantity')->default(1); // Số lượng mã có thể dùng
            $table->dateTime('expires_at')->nullable(); // Hạn sử dụng
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
