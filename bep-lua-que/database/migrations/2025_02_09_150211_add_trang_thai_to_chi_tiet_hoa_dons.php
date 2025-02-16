<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->enum('trang_thai', ['cho_che_bien', 'dang_nau', 'hoan_thanh'])->default('cho_che_bien');
        });
    }

    public function down()
    {
        Schema::table('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->dropColumn('trang_thai');
        });
    }
};
