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
            $table->text('ghi_chu')->nullable()->after('trang_thai'); // thay 'ten_cot_cuoi' bằng cột hiện có cuối cùng
        });
    }

    public function down()
    {
        Schema::table('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->dropColumn('ghi_chu');
        });
    }
};
