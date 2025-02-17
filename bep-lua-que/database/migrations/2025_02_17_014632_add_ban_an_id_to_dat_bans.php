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
        Schema::table('dat_bans', function (Blueprint $table) {
            $table->integer('ban_an_id')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dat_bans', function (Blueprint $table) {
            //
            $table->dropColumn('ban_an_id');  // Xóa cột khi rollback
        });
    }
};
