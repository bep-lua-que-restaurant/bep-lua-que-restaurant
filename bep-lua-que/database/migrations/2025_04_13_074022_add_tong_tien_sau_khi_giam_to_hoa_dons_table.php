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
            $table->decimal('tong_tien_sau_khi_giam', 15, 2)->nullable(); // thêm cột tổng tiền sau khi giảm
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hoa_dons', function (Blueprint $table) {
            //
        });
    }
};
