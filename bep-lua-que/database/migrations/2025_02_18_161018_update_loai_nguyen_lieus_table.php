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
        Schema::table('loai_nguyen_lieus', function (Blueprint $table) {
            // Thêm cột 'ma_loai' nếu chưa có
            if (!Schema::hasColumn('loai_nguyen_lieus', 'ma_loai')) {
                $table->string('ma_loai', 255)->unique()->after('id');
            }

            // Thêm cột 'ten_loai' nếu chưa có
            if (!Schema::hasColumn('loai_nguyen_lieus', 'ten_loai')) {
                $table->string('ten_loai', 255)->unique()->after('ma_loai');
            }

            // Thêm cột 'mo_ta' nếu chưa có
            if (!Schema::hasColumn('loai_nguyen_lieus', 'mo_ta')) {
                $table->text('mo_ta')->nullable()->after('ten_loai');
            }

            // Đảm bảo có các cột timestamps và softDeletes
            if (!Schema::hasColumn('loai_nguyen_lieus', 'created_at')) {
                $table->timestamps();
            }

            if (!Schema::hasColumn('loai_nguyen_lieus', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loai_nguyen_lieus', function (Blueprint $table) {
            // Xóa các cột khi rollback
            $table->dropColumn('ma_loai');
            $table->dropColumn('ten_loai');
            $table->dropColumn('mo_ta');
            $table->dropColumn('deleted_at');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
};
