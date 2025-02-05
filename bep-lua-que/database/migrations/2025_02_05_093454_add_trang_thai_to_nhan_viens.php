<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('nhan_viens', function (Blueprint $table) {
            $table->enum('trang_thai', ['dang_lam_viec', 'nghi_viec', 'tam_nghi'])->default('dang_lam_viec');
        });
    }

    public function down()
    {
        Schema::table('nhan_viens', function (Blueprint $table) {
            $table->dropColumn('trang_thai');
        });
    }
};
