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
        Schema::create('hoa_don_bans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ban_an_id');
            $table->unsignedBigInteger('hoa_don_id');
            $table->enum('trang_thai', ['dang_xu_ly', 'da_thanh_toan', 'da_huy']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoa_don_bans');
    }
};
