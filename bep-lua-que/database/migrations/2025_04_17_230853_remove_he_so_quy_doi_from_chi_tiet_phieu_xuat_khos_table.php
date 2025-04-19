<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveHeSoQuyDoiFromChiTietPhieuXuatKhosTable extends Migration
{
    public function up()
    {
        Schema::table('chi_tiet_phieu_xuat_khos', function (Blueprint $table) {
            $table->dropColumn('he_so_quy_doi');
        });
    }

    public function down()
    {
        Schema::table('chi_tiet_phieu_xuat_khos', function (Blueprint $table) {
            $table->float('he_so_quy_doi', 10, 4)->nullable()->after('don_vi_xuat');
        });
    }
}

