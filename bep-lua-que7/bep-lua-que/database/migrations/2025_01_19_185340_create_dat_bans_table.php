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
        Schema::create('dat_bans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('khach_hang_id');
            $table->string('so_dien_thoai', 15);
            $table->dateTime('thoi_gian_den');
            $table->integer('so_nguoi');
            $table->enum('trang_thai', ['dang_xu_ly', 'xac_nhan', 'da_huy']);
            $table->text('mo_ta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dat_bans');
    }
};
