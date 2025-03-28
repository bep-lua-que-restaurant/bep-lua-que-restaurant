<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('dat_bans', function (Blueprint $table) {
            $table->dropUnique('dat_bans_ma_dat_ban_unique'); // Xóa UNIQUE
        });
    }

    public function down()
    {
        Schema::table('dat_bans', function (Blueprint $table) {
            $table->unique('ma_dat_ban'); // Khôi phục UNIQUE nếu rollback
        });
    }
};
