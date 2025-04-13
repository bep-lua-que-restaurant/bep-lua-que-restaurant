<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hoa_dons', function (Blueprint $table) {
            $table->id();
            $table->string('ma_hoa_don', 20)->unique();
            $table->unsignedBigInteger('khach_hang_id');
            $table->decimal('tong_tien', 10, 2);
            $table->enum('phuong_thuc_thanh_toan', ['tien_mat', 'the','tai_khoan']);
            $table->text('mo_ta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoa_dons');
    }
};
