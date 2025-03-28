<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dat_bans', function (Blueprint $table) {
            // Thêm cột ma_dat_ban với độ dài 20 ký tự, có giá trị duy nhất
            $table->string('ma_dat_ban', 20)->after('id')->unique();

            // Thêm cột gio_du_kien kiểu TIME
            $table->time('gio_du_kien')->after('thoi_gian_den');
        });
    }

    public function down(): void
    {
        Schema::table('dat_bans', function (Blueprint $table) {
            $table->dropColumn(['ma_dat_ban', 'gio_du_kien']);
        });
    }
};
