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
        Schema::create('danh_muc_mon_ans', function (Blueprint $table) {
            $table->id();
            $table->string('ten', 255)->unique();
            $table->text('mo_ta')->nullable();
            $table->text('hinh_anh')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_muc_mon_ans');
    }
};
