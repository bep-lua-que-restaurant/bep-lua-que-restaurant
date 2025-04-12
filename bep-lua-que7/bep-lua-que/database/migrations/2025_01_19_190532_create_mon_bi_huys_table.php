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
        Schema::create('mon_bi_huys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('danh_muc_mon_an_id');
            $table->unsignedBigInteger('mon_an_id');
            $table->string('ten_mon')->unique();
            $table->string('hinh_anh')->nullable();
            $table->text('ly_do')->nullable();
            $table->integer('so_luong')->default(0);
            $table->integer('don_gia')->default(0);
            $table->dateTime('ngay_huy')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mon_bi_huys');
    }
};
