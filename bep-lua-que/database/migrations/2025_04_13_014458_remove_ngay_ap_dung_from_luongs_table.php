<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNgayApDungFromLuongsTable extends Migration
{
    public function up(): void
    {
        Schema::table('luongs', function (Blueprint $table) {
            $table->dropColumn('ngay_ap_dung'); // Xóa cột ngay_ap_dung
        });
    }

    public function down(): void
    {
        Schema::table('luongs', function (Blueprint $table) {
            $table->date('ngay_ap_dung')->after('muc_luong')->nullable(false); // Thêm lại cột ngay_ap_dung nếu rollback
        });
    }
}
