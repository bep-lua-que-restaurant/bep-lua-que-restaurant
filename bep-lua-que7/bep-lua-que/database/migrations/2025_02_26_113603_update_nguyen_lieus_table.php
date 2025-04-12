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
        Schema::table('nguyen_lieus', function (Blueprint $table) {
            $table->decimal('he_so_quy_doi', 10, 2)->default(1)->after('don_vi_tinh'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguyen_lieus', function (Blueprint $table) {
            $table->dropColumn('he_so_quy_doi');
        });
    }
};
