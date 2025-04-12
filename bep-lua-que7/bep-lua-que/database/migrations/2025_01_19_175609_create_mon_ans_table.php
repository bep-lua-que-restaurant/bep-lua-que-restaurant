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
        Schema::create('mon_ans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('danh_muc_mon_an_id');
            $table->string('ten', 255)->unique();
            $table->text('mo_ta')->nullable();
            $table->decimal('gia', 10, 2);
            $table->enum('trang_thai', ['dang_ban', 'het_hang', 'ngung_ban'])->default('dang_ban');
            $table->foreign('danh_muc_mon_an_id')->references('id')->on('danh_muc_mon_ans')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mon_ans');
    }
};
