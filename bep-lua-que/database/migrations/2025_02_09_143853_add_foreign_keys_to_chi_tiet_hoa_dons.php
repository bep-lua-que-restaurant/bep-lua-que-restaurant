<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chi_tiet_hoa_dons', function (Blueprint $table) {
            // Xóa khóa ngoại cũ nếu tồn tại
            // Thêm lại khóa ngoại đúng chuẩn
            $table->foreign('hoa_don_id')->references('id')->on('hoa_dons')->onDelete('cascade');
            $table->foreign('mon_an_id')->references('id')->on('mon_ans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->dropForeign(['hoa_don_id']);
            $table->dropForeign(['mon_an_id']);
        });
    }
};
