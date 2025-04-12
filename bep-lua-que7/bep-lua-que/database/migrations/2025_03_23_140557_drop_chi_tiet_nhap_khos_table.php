<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('chi_tiet_nhap_khos');
    }

    public function down(): void
    {
        Schema::create('chi_tiet_nhap_khos', function ($table) {
            $table->bigIncrements('id');
            $table->bigInteger('phieu_nhap_kho_id');
            $table->bigInteger('nguyen_lieu_id');
            $table->bigInteger('loai_nguyen_lieu_id');
            $table->bigInteger('kho_id');
            $table->decimal('so_luong', 15, 2);
            $table->decimal('gia_nhap', 15, 2);
            $table->decimal('thanh_tien', 15, 2);
            $table->tinyInteger('da_nhap_kho')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }
};

