<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('luongs', function (Blueprint $table) {
        $table->date('ngay_ap_dung')->after('muc_luong')->nullable(); // Tạo cột ngày áp dụng
    });
}

public function down()
{
    Schema::table('luongs', function (Blueprint $table) {
        $table->dropColumn('ngay_ap_dung'); // Xóa cột khi rollback
    });
}

};
