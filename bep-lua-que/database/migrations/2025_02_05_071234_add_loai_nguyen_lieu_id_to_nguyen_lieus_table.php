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
            $table->unsignedBigInteger('loai_nguyen_lieu_id')->nullable()->after('id');
            $table->foreign('loai_nguyen_lieu_id')->references('id')->on('loai_nguyen_lieus')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguyen_lieus', function (Blueprint $table) {
            //
        });
    }
};
