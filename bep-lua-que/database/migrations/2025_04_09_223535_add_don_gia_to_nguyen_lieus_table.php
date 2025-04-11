<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDonGiaToNguyenLieusTable extends Migration
{
    public function up(): void
    {
        Schema::table('nguyen_lieus', function (Blueprint $table) {
            $table->decimal('don_gia', 15, 2)->default(0)->after('so_luong_ton'); // Thêm cột đơn giá
        });
    }

    public function down(): void
    {
        Schema::table('nguyen_lieus', function (Blueprint $table) {
            $table->dropColumn('don_gia'); // Xóa cột đơn giá nếu rollback
        });
    }
}