<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('loai_nguyen_lieus');
    }

    public function down(): void
    {
        Schema::create('loai_nguyen_lieus', function ($table) {
            $table->bigIncrements('id');
            $table->string('ma_loai', 255);
            $table->string('ten_loai', 255);
            $table->text('mo_ta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
};

