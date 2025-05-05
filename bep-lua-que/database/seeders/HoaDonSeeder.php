<?php

namespace Database\Seeders;

use App\Models\HoaDon;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HoaDonSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        // Lấy danh sách hoa_don_id và created_at từ chi_tiet_hoa_dons
        $hoaDonList = DB::table('chi_tiet_hoa_dons')
            ->select('hoa_don_id', DB::raw('MIN(created_at) as created_at'))
            ->whereBetween('hoa_don_id', [5479, 5928])
            ->groupBy('hoa_don_id')
            ->pluck('created_at', 'hoa_don_id')
            ->toArray();

        foreach ($hoaDonList as $hoaDonId => $createdAt) {
            // Tính tổng tiền từ chi_tiet_hoa_dons
            $tongTien = DB::table('chi_tiet_hoa_dons')
                ->where('hoa_don_id', $hoaDonId)
                ->sum('thanh_tien');

            // Nếu không có tổng tiền, bỏ qua
            if ($tongTien == 0) {
                continue;
            }

            $randomDate = Carbon::parse($createdAt);

            $data[] = [
                'id' => $hoaDonId,
                'ma_hoa_don' => 'HD-' . $randomDate->format('Ymd') . '-' . Str::random(4),
                'ma_dat_ban' => null,
                'tong_tien' => $tongTien, // Lấy từ chi_tiet_hoa_dons
                'phuong_thuc_thanh_toan' => collect(['tien_mat', 'the', 'tai_khoan'])->random(),
                'mo_ta' => null,
                'deleted_at' => null,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ];
        }

        // Xóa dữ liệu cũ
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('hoa_dons')->truncate();
        DB::table('hoa_dons')->insert($data);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}