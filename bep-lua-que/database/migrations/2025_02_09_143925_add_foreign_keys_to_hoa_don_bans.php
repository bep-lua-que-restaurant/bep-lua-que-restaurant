<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hoa_don_bans', function (Blueprint $table) {


            // Đảm bảo cột có kiểu dữ liệu phù hợp
            $table->unsignedBigInteger('hoa_don_id')->change();
            $table->unsignedBigInteger('ban_an_id')->change();

            // Thêm lại khóa ngoại mới
            $table->foreign('hoa_don_id')->references('id')->on('hoa_dons')->onDelete('cascade');
            $table->foreign('ban_an_id')->references('id')->on('ban_ans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hoa_don_bans', function (Blueprint $table) {
            $table->dropForeign(['hoa_don_id']);
            $table->dropForeign(['ban_an_id']);
        });
    }
};
