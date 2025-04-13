<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chi_tiet_hoa_dons', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE chi_tiet_hoa_dons MODIFY trang_thai ENUM('cho_xac_nhan', 'cho_che_bien', 'dang_nau', 'hoan_thanh') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cho_xac_nhan'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chi_tiet_hoa_dons', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE chi_tiet_hoa_dons MODIFY trang_thai ENUM('cho_che_bien', 'dang_nau', 'hoan_thanh') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cho_che_bien'");
        });
    }
};
