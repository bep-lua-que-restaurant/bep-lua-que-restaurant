<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HoaDonBanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        // Khoảng thời gian từ tháng 9/2024 đến tháng 5/2025
        $startDate = Carbon::create(2024, 9, 1);
        $endDate = Carbon::create(2025, 5, 31);

        // Tính số tháng
        $months = $startDate->diffInMonths($endDate) + 1; // 9 tháng
        $recordsPerMonth = 50; // Số bản ghi mỗi tháng
        $hoaDonId = 5479; // Bắt đầu từ hoa_don_id = 5479

        for ($month = 0; $month < $months; $month++) {
            $currentMonthStart = $startDate->copy()->addMonths($month)->startOfMonth();
            $currentMonthEnd = $currentMonthStart->copy()->endOfMonth();

            for ($i = 0; $i < $recordsPerMonth; $i++) {
                $randomDate = Carbon::createFromTimestamp(mt_rand(
                    $currentMonthStart->timestamp,
                    $currentMonthEnd->timestamp
                ));

                $data[] = [
                    'ban_an_id' => rand(180, 199), // Bàn từ 180 đến 199
                    'hoa_don_id' => $hoaDonId, // Tăng dần từ 5479
                    'trang_thai' => 'da_thanh_toan',
                    'deleted_at' => null,
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ];

                $hoaDonId++;
            }
        }

        // Xóa dữ liệu cũ
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('hoa_don_bans')->truncate();
        DB::table('hoa_don_bans')->insert($data);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}