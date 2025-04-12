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
            $table->float('thoi_gian_nau', 8, 2)->change(); // Sửa cột 'thoi_gian_nau' thành kiểu float
        });
    }

    public function down()
    {
        Schema::table('mon_ans', function (Blueprint $table) {
            $table->integer('thoi_gian_nau')->change(); // Quay lại kiểu integer nếu cần
        });
    }
};
