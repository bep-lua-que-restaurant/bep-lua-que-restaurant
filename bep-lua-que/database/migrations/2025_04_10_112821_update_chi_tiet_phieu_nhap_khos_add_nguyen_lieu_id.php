<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateChiTietPhieuNhapKhosAddNguyenLieuId extends Migration
{
    public function up()
    {
        Schema::table('chi_tiet_phieu_nhap_khos', function (Blueprint $table) {
            // Thêm cột mới
            $table->unsignedBigInteger('nguyen_lieu_id')->nullable()->after('loai_nguyen_lieu_id');

            // Tạo khóa ngoại
            $table->foreign('nguyen_lieu_id')
                ->references('id')->on('nguyen_lieus')
                ->onDelete('set null'); // Không xóa dữ liệu chi tiết nếu xóa nguyên liệu
        });
    }

    public function down()
    {
        Schema::table('chi_tiet_phieu_nhap_khos', function (Blueprint $table) {
            $table->dropForeign(['nguyen_lieu_id']);
            $table->dropColumn('nguyen_lieu_id');
        });
    }
}
