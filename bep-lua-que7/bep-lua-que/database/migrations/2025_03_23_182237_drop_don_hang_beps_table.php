<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('don_hang_beps');
    }

    public function down(): void
    {
        Schema::create('don_hang_beps', function (Blueprint $table) {
            $table->id();
            // Thêm các cột cần thiết nếu muốn rollback
            $table->timestamps();
        });
    }
};
