<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('phieu_nhap_khos', function (Blueprint $table) {
            $table->enum('loai_phieu', ['nhap_tu_bep', 'nhap_tu_ncc'])->after('ghi_chu')->nullable();
        });
    }

    public function down()
    {
        Schema::table('phieu_nhap_khos', function (Blueprint $table) {
            $table->dropColumn('loai_phieu');
        });
    }
};
