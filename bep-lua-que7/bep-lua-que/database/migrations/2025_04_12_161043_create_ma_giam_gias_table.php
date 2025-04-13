<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ma_giam_gias', function (Blueprint $table) {
            $table->id();
            $table->string('mã_giảm_giá', 20)->unique();
            $table->enum('loại', ['percentage', 'fixed']);
            $table->decimal('giá_trị', 10, 2)->unsigned();
            $table->decimal('giá_trị_đơn_hàng_tối_thiểu', 10, 2)->nullable()->unsigned();
            $table->date('ngày_bắt_đầu');
            $table->date('ngày_kết_thúc');
            $table->integer('giới_hạn_sử_dụng')->nullable()->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ma_giam_gias');
    }
};
