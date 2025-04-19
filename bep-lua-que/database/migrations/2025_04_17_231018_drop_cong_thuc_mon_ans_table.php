<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropCongThucMonAnsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('cong_thuc_mon_ans');
    }

    public function down()
    {
        Schema::create('cong_thuc_mon_ans', function ($table) {
            $table->id();
            $table->unsignedBigInteger('mon_an_id');
            $table->unsignedBigInteger('nguyen_lieu_id');
            $table->float('so_luong', 8, 2); // số lượng nguyên liệu sử dụng
            $table->string('don_vi')->nullable(); // đơn vị: gram, cái, ml...
            $table->timestamps();

            $table->foreign('mon_an_id')->references('id')->on('mon_ans')->onDelete('cascade');
            $table->foreign('nguyen_lieu_id')->references('id')->on('nguyen_lieus')->onDelete('cascade');
            $table->unique(['mon_an_id', 'nguyen_lieu_id']); // tránh trùng công thức
        });
    }
}
