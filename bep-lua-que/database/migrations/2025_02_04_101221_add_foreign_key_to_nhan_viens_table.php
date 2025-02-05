<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nhan_viens', function (Blueprint $table) {
            $table->foreign('chuc_vu_id')->references('id')->on('chuc_vus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('nhan_viens', function (Blueprint $table) {
            $table->dropForeign(['chuc_vu_id']);
        });
    }
};
