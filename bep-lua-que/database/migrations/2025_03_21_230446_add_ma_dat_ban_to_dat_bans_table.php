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
        Schema::table('dat_bans', function (Blueprint $table) {
            $table->enum('trang_thai', ['dang_xu_ly', 'xac_nhan', 'da_huy', 'da_thanh_toan'])
                ->default('dang_xu_ly')
                ->change();
        });
    }

    public function down()
    {
        Schema::table('dat_bans', function (Blueprint $table) {
            $table->enum('trang_thai', ['dang_xu_ly', 'xac_nhan', 'da_huy'])
                ->default('dang_xu_ly')
                ->change();
        });
    }
};
