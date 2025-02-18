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
        Schema::table('nha_cung_caps', function (Blueprint $table) {
            // Kiểm tra xem các cột đã tồn tại chưa
            if (!Schema::hasColumn('nha_cung_caps', 'ma_nha_cung_cap')) {
                $table->string('ma_nha_cung_cap', 50)->unique()->after('id');
            }
            if (!Schema::hasColumn('nha_cung_caps', 'so_dien_thoai')) {
                $table->string('so_dien_thoai', 20)->unique()->after('ma_nha_cung_cap');
            }
            if (!Schema::hasColumn('nha_cung_caps', 'email')) {
                $table->string('email', 255)->unique()->after('so_dien_thoai');
            }
            if (!Schema::hasColumn('nha_cung_caps', 'ghi_chu')) {
                $table->text('ghi_chu')->nullable()->after('email');
            }
            if (!Schema::hasColumn('nha_cung_caps', 'ten_nha_cung_cap')) {
                $table->string('ten_nha_cung_cap', 255)->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nha_cung_caps', function (Blueprint $table) {
            // Kiểm tra và xóa các cột nếu chúng tồn tại
            if (Schema::hasColumn('nha_cung_caps', 'ma_nha_cung_cap')) {
                $table->dropColumn('ma_nha_cung_cap');
            }
            if (Schema::hasColumn('nha_cung_caps', 'so_dien_thoai')) {
                $table->dropColumn('so_dien_thoai');
            }
            if (Schema::hasColumn('nha_cung_caps', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('nha_cung_caps', 'ghi_chu')) {
                $table->dropColumn('ghi_chu');
            }
            if (Schema::hasColumn('nha_cung_caps', 'ten_nha_cung_cap')) {
                $table->dropColumn('ten_nha_cung_cap');
            }
        });
    }
};
