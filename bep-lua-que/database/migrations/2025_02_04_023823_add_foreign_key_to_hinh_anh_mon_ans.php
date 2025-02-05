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
        Schema::table('hinh_anh_mon_ans', function (Blueprint $table) {
            // Nếu đã có cột mon_an_id nhưng chưa có khóa ngoại, thêm khóa ngoại
            $table->foreign('mon_an_id')->references('id')->on('mon_ans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hinh_anh_mon_ans', function (Blueprint $table) {
            // Xóa khóa ngoại nếu rollback
            $table->dropForeign(['mon_an_id']);
        });
    }
};
