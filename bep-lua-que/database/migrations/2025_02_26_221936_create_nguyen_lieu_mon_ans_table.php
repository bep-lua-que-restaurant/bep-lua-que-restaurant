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
        Schema::create('nguyen_lieu_mon_ans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mon_an_id');
            $table->unsignedBigInteger('nguyen_lieu_id');
            $table->decimal('so_luong', 10, 2); // Số lượng nguyên liệu cần cho món ăn
            $table->string('don_vi_tinh'); // Đơn vị tính nguyên liệu (vd: gam, ml,...)
            $table->timestamps();
        
            // Khóa ngoại
            $table->foreign('mon_an_id')->references('id')->on('mon_ans')->onDelete('cascade');
            $table->foreign('nguyen_lieu_id')->references('id')->on('nguyen_lieus')->onDelete('cascade');
        
            // Đảm bảo mỗi nguyên liệu chỉ có một lần trong một món ăn
            $table->unique(['mon_an_id', 'nguyen_lieu_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguyen_lieu_mon_ans');
    }
};
