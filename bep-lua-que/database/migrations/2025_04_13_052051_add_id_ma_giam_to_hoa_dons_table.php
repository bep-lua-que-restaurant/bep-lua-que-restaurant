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
            $table->unsignedInteger('id_ma_giam')->nullable(); // Thêm cột id_ma_giam
        });
    }

    public function down()
    {
        Schema::table('hoa_dons', function (Blueprint $table) {
            $table->dropColumn('id_ma_giam'); // Xóa cột khi rollback migration
        });
    }
};
