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
        Schema::table('chi_tiet_phieu_nhap_khos', function (Blueprint $table) {
            $table->string('don_vi_nhap', 50)->after('so_luong');
            $table->decimal('so_luong_quy_doi', 15, 2)->after('don_vi_nhap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chi_tiet_phieu_nhap_khos', function (Blueprint $table) {
            $table->dropColumn(['don_vi_nhap', 'so_luong_quy_doi']);
        });
    }
};
