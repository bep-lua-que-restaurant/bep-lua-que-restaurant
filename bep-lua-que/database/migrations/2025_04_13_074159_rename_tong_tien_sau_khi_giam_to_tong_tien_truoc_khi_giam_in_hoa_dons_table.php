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
        Schema::table('hoa_dons', function (Blueprint $table) {
            $table->renameColumn('tong_tien_sau_khi_giam', 'tong_tien_truoc_khi_giam'); // Đổi tên cột
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('hoa_dons', function (Blueprint $table) {
            $table->renameColumn('tong_tien_truoc_khi_giam', 'tong_tien_sau_khi_giam'); // Đổi lại tên cột nếu rollback
        });
    }
    
};
