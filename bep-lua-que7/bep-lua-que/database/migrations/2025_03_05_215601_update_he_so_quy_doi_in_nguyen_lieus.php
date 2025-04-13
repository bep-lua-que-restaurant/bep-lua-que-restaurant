<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('nguyen_lieus', function (Blueprint $table) {
            $table->float('he_so_quy_doi')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('chi_tiet_phieu_nhap_khos', function (Blueprint $table) {
            $table->decimal('he_so_quy_doi', 10, 2)->default(1.00)->change();
        });
    }
};