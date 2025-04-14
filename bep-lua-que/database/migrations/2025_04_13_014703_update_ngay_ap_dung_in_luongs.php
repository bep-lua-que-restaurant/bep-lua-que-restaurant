<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateNgayApDungInLuongs extends Migration
{
    public function up(): void
    {
        // Cập nhật tất cả các bản ghi có giá trị '0000-00-00' trong cột ngay_ap_dung
        DB::table('luongs')->where('ngay_ap_dung', '0000-00-00')->update([
            'ngay_ap_dung' => '2025-01-01', // Cập nhật bằng giá trị hợp lệ
        ]);
    }

    public function down(): void
    {
        // Rollback nếu cần
    }
}
