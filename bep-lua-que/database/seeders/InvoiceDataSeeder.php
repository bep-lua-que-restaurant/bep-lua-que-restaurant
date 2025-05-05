<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceDataSeeder extends Seeder
{
    public function run()
    {
        // Danh sách bàn ăn và món ăn từ dữ liệu cung cấp
        $banAns = range(180, 199); // ID bàn từ 180 đến 199
        $monAns = DB::table('mon_ans')
            ->where('trang_thai', 'dang_ban')
            ->whereNull('deleted_at')
            ->select('id', 'gia', 'thoi_gian_nau')
            ->get()
            ->toArray();

        // Tạo dữ liệu từ tháng 9/2024 đến tháng 4/2025
        $startDate = Carbon::create(2024, 9, 1);
        $endDate = Carbon::create(2025, 4, 30);
        $invoiceId = 1;

        while ($startDate->lte($endDate)) {
            $month = $startDate->month;
            $year = $startDate->year;

            // Tạo 30 hóa đơn mỗi tháng
            for ($i = 1; $i <= 30; $i++) {
                // Tạo thời gian ngẫu nhiên trong tháng
                $createdAt = Carbon::create($year, $month, rand(1, $startDate->daysInMonth), rand(8, 22), rand(0, 59), rand(0, 59));

                // Tạo hóa đơn
                $tongTien = 0;
                $phuongThucThanhToan = ['tien_mat', 'the', 'tai_khoan'][rand(0, 2)];
                $hoaDonId = DB::table('hoa_dons')->insertGetId([
                    'ma_hoa_don' => sprintf('HD%04d', $invoiceId),
                    'ma_dat_ban' => null,
                    'tong_tien' => 0, // Sẽ cập nhật sau
                    'phuong_thuc_thanh_toan' => $phuongThucThanhToan,
                    'mo_ta' => null,
                    'id_ma_giam' => null,
                    'tong_tien_truoc_khi_giam' => 0, // Sẽ cập nhật sau
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // Tạo hóa đơn bàn
                DB::table('hoa_don_bans')->insert([
                    'ban_an_id' => $banAns[array_rand($banAns)],
                    'hoa_don_id' => $hoaDonId,
                    'trang_thai' => 'da_thanh_toan',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // Tạo chi tiết hóa đơn (ngẫu nhiên 1-5 món)
                $numItems = rand(1, 5);
                for ($j = 0; $j < $numItems; $j++) {
                    $monAn = $monAns[array_rand($monAns)];
                    $soLuong = rand(1, 5);
                    $donGia = $monAn->gia;
                    $thanhTien = $soLuong * $donGia;
                    $tongTien += $thanhTien;

                    $trangThai = 'hoan_thanh';

                    $thoiGianNau = $monAn->thoi_gian_nau;
                    $thoiGianBatDauNau = $createdAt;
                    $thoiGianHoanThanhDuKien = $thoiGianBatDauNau->copy()->addMinutes($thoiGianNau);
                    $thoiGianHoanThanhThucTe = $trangThai === 'da_hoan_thanh'
                        ? $thoiGianHoanThanhDuKien->copy()->addMinutes(rand(0, 2))
                        : null;

                    DB::table('chi_tiet_hoa_dons')->insert([
                        'hoa_don_id' => $hoaDonId,
                        'mon_an_id' => $monAn->id,
                        'so_luong' => $soLuong,
                        'don_gia' => $donGia,
                        'thanh_tien' => $thanhTien,
                        'trang_thai' => $trangThai,
                        'ghi_chu' => null,
                        'thoi_gian_bat_dau_nau' => $thoiGianBatDauNau,
                        'thoi_gian_hoan_thanh_du_kien' => $thoiGianHoanThanhDuKien,
                        'thoi_gian_hoan_thanh_thuc_te' => $thoiGianHoanThanhThucTe,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }

                // Cập nhật tổng tiền cho hóa đơn
                DB::table('hoa_dons')
                    ->where('id', $hoaDonId)
                    ->update([
                        'tong_tien' => $tongTien,
                        'tong_tien_truoc_khi_giam' => $tongTien,
                    ]);

                $invoiceId++;
            }

            $startDate->addMonth();
        }
    }
}