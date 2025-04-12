<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lich_su_dat_bans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ban_an_id');
            $table->unsignedBigInteger('dat_ban_id');
            $table->enum('trang_thai', ['dang_xu_ly', 'da_thanh_toan', 'da_huy']);
            $table->dateTime('thoi_gian_vao');
            $table->dateTime('thoi_gian_ra')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_dat_bans');
    }
};
