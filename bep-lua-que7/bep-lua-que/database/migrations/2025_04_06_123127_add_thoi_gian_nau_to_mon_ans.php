<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('mon_ans', function (Blueprint $table) {
            // Thêm cột `thoi_gian_nau` vào bảng `mon_ans`
            $table->integer('thoi_gian_nau')->default(0); // thời gian nấu dự kiến (tính bằng phút)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mon_ans', function (Blueprint $table) {
            // Xóa cột `thoi_gian_nau` nếu cần rollback
            $table->dropColumn('thoi_gian_nau');
        });
    }
};
