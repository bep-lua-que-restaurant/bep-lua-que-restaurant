<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bang_tinh_luongs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nhan_vien_id'); // Liên kết với nhân viên
            $table->date('thang_nam'); // Tháng tính lương (YYYY-MM-01)
            $table->integer('so_ca_lam')->default(0); // Tổng số ca làm trong tháng
            $table->decimal('so_ngay_cong', 5, 2)->default(0); // Số ngày công thực tế
            $table->decimal('tong_luong', 12, 2)->default(0); // Tổng lương thực nhận
            $table->text('ghi_chu')->nullable(); // Ghi chú (đi trễ, nghỉ, thưởng/phạt)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bang_tinh_luongs');
    }
};
