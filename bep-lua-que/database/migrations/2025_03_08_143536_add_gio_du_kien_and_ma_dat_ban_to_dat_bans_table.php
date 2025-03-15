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
            $table->string('ma_dat_ban', 20)->unique()->after('id');
            $table->time('gio_du_kien')->after('thoi_gian_den');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('dat_bans', function (Blueprint $table) {
            $table->dropColumn(['ma_dat_ban', 'gio_du_kien']);
        });
    }
};
