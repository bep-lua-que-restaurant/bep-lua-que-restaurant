<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTenMonColumnFromMonBiHuys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mon_bi_huys', function (Blueprint $table) {
            $table->dropColumn('ten_mon');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mon_bi_huys', function (Blueprint $table) {
            // Khôi phục cột nếu cần rollback
            $table->string('ten_mon')->unique();
        });
    }
}