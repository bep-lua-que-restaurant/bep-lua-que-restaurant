<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoaiNguyenLieusTable extends Migration
{
    public function up(): void
    {
        Schema::create('loai_nguyen_lieus', function (Blueprint $table) {
            $table->id();
            $table->string('ten_loai');
            $table->text('ghi_chu')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loai_nguyen_lieus');
    }
}
