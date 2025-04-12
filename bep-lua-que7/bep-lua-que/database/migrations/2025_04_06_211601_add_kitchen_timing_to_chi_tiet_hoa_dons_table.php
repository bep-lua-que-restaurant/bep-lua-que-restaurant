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
        Schema::table('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->timestamp('thoi_gian_bat_dau_nau')->nullable()->after('ghi_chu');
            $table->timestamp('thoi_gian_hoan_thanh_du_kien')->nullable()->after('thoi_gian_bat_dau_nau');
            $table->timestamp('thoi_gian_hoan_thanh_thuc_te')->nullable()->after('thoi_gian_hoan_thanh_du_kien');
        });
    }
    
    public function down()
    {
        Schema::table('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->dropColumn([
                'thoi_gian_bat_dau_nau',
                'thoi_gian_hoan_thanh_du_kien',
                'thoi_gian_hoan_thanh_thuc_te'
            ]);
        });
    }
    
};
