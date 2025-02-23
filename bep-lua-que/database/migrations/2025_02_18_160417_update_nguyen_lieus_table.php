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
        Schema::table('nguyen_lieus', function (Blueprint $table) {
            // Kiểm tra nếu cột chưa tồn tại thì thêm vào
            if (!Schema::hasColumn('nguyen_lieus', 'so_luong_ton_toi_thieu')) {
                $table->decimal('so_luong_ton_toi_thieu', 15, 2)->default(0)->after('so_luong_ton');
            }

            if (!Schema::hasColumn('nguyen_lieus', 'so_luong_ton_toi_da')) {
                $table->decimal('so_luong_ton_toi_da', 15, 2)->default(0)->after('so_luong_ton_toi_thieu');
            }

            // Cập nhật độ dài cột 'ma_nguyen_lieu' và 'ten_nguyen_lieu'
            $table->string('ma_nguyen_lieu', 50)->change(); // Độ dài giảm xuống 50
            $table->string('ten_nguyen_lieu', 255)->change(); // Độ dài 255

            // Đảm bảo khóa ngoại cho 'loai_nguyen_lieu_id' nếu chưa có
            if (!Schema::hasColumn('nguyen_lieus', 'loai_nguyen_lieu_id')) {
                $table->foreignId('loai_nguyen_lieu_id')->nullable()->constrained('loai_nguyen_lieus')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguyen_lieus', function (Blueprint $table) {
            // Xóa các cột đã thêm
            $table->dropColumn('so_luong_ton_toi_thieu');
            $table->dropColumn('so_luong_ton_toi_da');

            // Đổi lại độ dài cột nếu cần
            $table->string('ma_nguyen_lieu', 255)->change();
            $table->string('ten_nguyen_lieu', 255)->change();

            // Xóa khóa ngoại nếu đã thêm
            $table->dropForeign(['loai_nguyen_lieu_id']);
        });
    }
};
