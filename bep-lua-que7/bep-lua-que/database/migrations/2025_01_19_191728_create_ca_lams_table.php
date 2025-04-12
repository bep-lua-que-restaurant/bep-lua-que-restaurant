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
        Schema::create('ca_lams', function (Blueprint $table) {
            $table->id();
            $table->string('ten_ca')->unique();
            $table->time('gio_bat_dau');
            $table->time('gio_ket_thuc');
            $table->softDeletes();
            $table->string('mo_ta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ca_lams');
    }
};
