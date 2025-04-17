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
        Schema::table('bang_tinh_luongs', function (Blueprint $table) {
            $table->integer('thuong_phat')->default(0)->after('tong_luong'); // Đặt sau cột tong_luong (nếu có)
        });
    }
    
    public function down(): void
    {
        Schema::table('bang_tinh_luongs', function (Blueprint $table) {
            $table->dropColumn('thuong_phat');
        });
    }
    
};
