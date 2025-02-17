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
        Schema::table('dat_bans', function (Blueprint $table) {
            $table->unsignedBigInteger('ban_an_id')->nullable(); // Thêm cột ban_an_id, có thể NULL
            $table->foreign('ban_an_id')->references('id')->on('ban_ans')->onDelete('cascade'); // Thiết lập khóa ngoại (nếu cần)
        });
    }

    public function down()
    {
        Schema::table('dat_bans', function (Blueprint $table) {
            $table->dropForeign(['ban_an_id']); // Xóa khóa ngoại nếu có
            $table->dropColumn('ban_an_id'); // Xóa cột ban_an_id
        });
    }
};
