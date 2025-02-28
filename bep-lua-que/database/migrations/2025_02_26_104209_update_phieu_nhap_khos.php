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
        Schema::table('phieu_nhap_khos', function (Blueprint $table) {
            $table->text('ghi_chu')->nullable()->after('ngay_nhap'); // Thêm cột ghi_chu sau ngay_nhap
            $table->dropColumn('tong_tien'); // Xóa cột tong_tien
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phieu_nhap_khos', function (Blueprint $table) {
            $table->decimal('tong_tien', 15, 2)->nullable(); // Thêm lại cột tong_tien nếu rollback
            $table->dropColumn('ghi_chu'); // Xóa cột ghi_chu nếu rollback
        });
    }
};
