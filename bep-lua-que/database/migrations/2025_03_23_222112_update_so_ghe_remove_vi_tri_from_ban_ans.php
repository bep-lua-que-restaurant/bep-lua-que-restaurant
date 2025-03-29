<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ban_ans', function (Blueprint $table) {
            // Đặt mặc định so_ghe = 4
            $table->integer('so_ghe')->default(4)->change();

            // Xóa cột vi_tri
            $table->dropColumn('vi_tri');
        });
    }

    public function down(): void
    {
        Schema::table('ban_ans', function (Blueprint $table) {
            // Nếu rollback, phục hồi cột vi_tri
            $table->string('vi_tri', 50)->nullable();

            // Nếu rollback, bỏ default của so_ghe
            $table->integer('so_ghe')->change();
        });
    }
};
