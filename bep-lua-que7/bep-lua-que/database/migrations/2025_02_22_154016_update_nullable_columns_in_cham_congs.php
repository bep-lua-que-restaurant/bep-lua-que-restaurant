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
        Schema::table('cham_congs', function (Blueprint $table) {
            $table->time('gio_vao_lam')->nullable()->change();
            $table->time('gio_ket_thuc')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('cham_congs', function (Blueprint $table) {
            $table->time('gio_vao_lam')->nullable(false)->change();
            $table->time('gio_ket_thuc')->nullable(false)->change();
        });
    }
};
