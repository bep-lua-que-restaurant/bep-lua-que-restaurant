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
        Schema::create('don_hang_beps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hoa_don_id');
            $table->unsignedBigInteger('mon_an_id');
            $table->integer('so_luong');
            $table->enum('trang_thai', ['dang_xu_ly', 'da_xong']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('don_hang_beps');
    }
};
