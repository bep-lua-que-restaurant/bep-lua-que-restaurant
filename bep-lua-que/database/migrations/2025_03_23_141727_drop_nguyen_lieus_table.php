<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('nguyen_lieus');
    }

    public function down(): void
    {
        Schema::create('nguyen_lieus', function ($table) {
            $table->bigIncrements('id');
            $table->bigInteger('loai_nguyen_lieu_id');
            $table->string('ma_nguyen_lieu', 50);
            $table->string('ten_nguyen_lieu', 255);
            $table->string('don_vi_tinh', 50);
            $table->double('he_so_quy_doi', 8, 2)->default(1);
            $table->decimal('so_luong_ton', 15, 2)->default(0);
            $table->decimal('gia_nhap', 15, 2)->nullable();
            $table->string('hinh_anh', 255)->nullable();
            $table->text('mo_ta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
};

