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
        Schema::create('ban_ans', function (Blueprint $table) {
            $table->id();
            $table->string('ten_ban', 20)->unique();
            $table->integer('so_ghe');
            $table->string('vi_tri', 50);
            $table->enum('trang_thai', ['trong', 'co_khach', 'da_dat_truoc']);
            $table->text('mo_ta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ban_ans');
    }
};
