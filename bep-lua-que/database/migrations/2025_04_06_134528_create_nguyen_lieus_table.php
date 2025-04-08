<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNguyenLieusTable extends Migration
{
    public function up(): void
    {
        Schema::create('nguyen_lieus', function (Blueprint $table) {
            $table->id();
            $table->string('ten_nguyen_lieu');
            $table->unsignedBigInteger('loai_nguyen_lieu_id');
            $table->string('don_vi_ton'); // đơn vị tồn kho mặc định (VD: gram, lít)
            $table->decimal('so_luong_ton', 10, 2)->default(0); // số lượng tồn theo đơn vị tồn
            $table->text('ghi_chu')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('loai_nguyen_lieu_id')->references('id')->on('loai_nguyen_lieus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nguyen_lieus');
    }
}
