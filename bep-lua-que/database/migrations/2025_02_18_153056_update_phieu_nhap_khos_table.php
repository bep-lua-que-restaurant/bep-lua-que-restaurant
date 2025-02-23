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
            // Kiểm tra xem cột 'tong_tien' đã tồn tại chưa
            if (!Schema::hasColumn('phieu_nhap_khos', 'tong_tien')) {
                $table->decimal('tong_tien', 15, 2)->default(0.00)->after('ngay_nhap');
            }

            // Kiểm tra xem cột 'trang_thai' đã tồn tại chưa
            if (!Schema::hasColumn('phieu_nhap_khos', 'trang_thai')) {
                $table->enum('trang_thai', ['cho_duyet', 'da_duyet', 'hoan_thanh', 'huy'])->default('cho_duyet')->after('tong_tien');
            }

            // Xóa khóa ngoại nếu tồn tại
            if (Schema::hasTable('phieu_nhap_khos')) {
                $table->dropForeign(['nhan_vien_id']);
                $table->dropForeign(['nha_cung_cap_id']);
            }

            // Định nghĩa lại khóa ngoại
            $table->foreign('nhan_vien_id')->references('id')->on('nhan_viens')->onDelete('cascade');
            $table->foreign('nha_cung_cap_id')->references('id')->on('nha_cung_caps')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phieu_nhap_khos', function (Blueprint $table) {
            // Xóa các cột mới thêm vào
            $table->dropColumn('tong_tien');
            $table->dropColumn('trang_thai');

            // Xóa khóa ngoại
            $table->dropForeign(['nhan_vien_id']);
            $table->dropForeign(['nha_cung_cap_id']);
        });
    }
};
