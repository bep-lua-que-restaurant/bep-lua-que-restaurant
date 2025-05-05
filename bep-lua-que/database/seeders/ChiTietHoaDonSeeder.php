<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChiTietHoaDonSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy danh sách món ăn với trạng thái 'dang_ban'
        $monAnList = DB::table('mon_ans')
            ->where('trang_thai', 'dang_ban')
            ->pluck('gia', 'id')
            ->toArray();

        // Lấy danh sách hóa đơn từ hoa_don_bans
        $hoaDonList = DB::table('hoa_don_bans')
            ->whereBetween('hoa_don_id', [5479, 5928])
            ->pluck('created_at', 'hoa_don_id')
            ->toArray();

        $data = [];

        foreach ($hoaDonList as $hoaDonId => $hoaDonTime) {
            $monAnRandom = array_rand($monAnList, rand(1, min(5, count($monAnList))));
            $monAnRandom = is_array($monAnRandom) ? $monAnRandom : [$monAnRandom];

            foreach ($monAnRandom as $monAnId) {
                $soLuong = rand(1, 5);
                $donGia = $monAnList[$monAnId];
                $thanhTien = $soLuong * $donGia;
                $createdAt = Carbon::parse($hoaDonTime)->addMinutes(rand(1, 30));

                $data[] = [
                    'hoa_don_id' => $hoaDonId,
                    'mon_an_id' => $monAnId,
                    'so_luong' => $soLuong,
                    'don_gia' => $donGia,
                    'thanh_tien' => $thanhTien,
                    'deleted_at' => null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt->copy()->addMinutes(rand(5, 20)),
                    'trang_thai' => 'hoan_thanh',
                ];
            }
        }

        // Xóa dữ liệu cũ
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('chi_tiet_hoa_dons')->truncate();
        DB::table('chi_tiet_hoa_dons')->insert($data);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}