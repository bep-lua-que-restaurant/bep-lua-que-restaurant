<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateChiTietPhieuNhapKhosTable extends Migration
{
    public function up()
    {
        Schema::table('chi_tiet_phieu_nhap_khos', function (Blueprint $table) {
            // Xoá cột
            $table->dropColumn(['don_vi_ton', 'he_so_quy_doi']);

          
        });
    }

    public function down()
    {
        Schema::table('chi_tiet_phieu_nhap_khos', function (Blueprint $table) {
            // Thêm lại cột khi rollback
            $table->string('don_vi_ton')->nullable()->after('ten_nguyen_lieu');
            $table->decimal('he_so_quy_doi', 10, 4)->nullable()->after('so_luong_nhap');

            
        });
    }
}
