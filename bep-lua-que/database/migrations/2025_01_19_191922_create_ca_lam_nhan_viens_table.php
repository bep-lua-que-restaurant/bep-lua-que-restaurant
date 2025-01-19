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
        Schema::create('ca_lam_nhan_viens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ca_lam_id');
            $table->unsignedBigInteger('nhan_vien_id');
            $table->date('ngay_lam');
            $table->time('gio_bat_dau');
            $table->time('gio_ket_thuc');
            $table->string('mo_ta')->nullable();
            $table->enum('trang_thai',['hoat_dong','ngung_lam'])->default('hoat_dong');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ca_lam_nhan_viens');
    }
};
