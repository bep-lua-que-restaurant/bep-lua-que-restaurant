<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('cong_thuc');
    }

    public function down(): void
    {
        Schema::create('cong_thuc', function ($table) {
            $table->bigIncrements('id');
            $table->bigInteger('mon_an_id');
            $table->bigInteger('nguyen_lieu_id');
            $table->decimal('so_luong', 10, 2)->default(0);
            $table->string('don_vi_tinh', 255);
            $table->timestamps();
        });
    }
};

