<?php

namespace App\Http\Controllers;

use App\Models\BanAn;
use App\Models\ChiTietHoaDon;
use App\Models\HoaDon;
use App\Models\HoaDonBan;
use App\Models\MonAn;
use Illuminate\Http\Request;

class TachBanController extends Controller
{
    public function getDon(Request $request)
    {
        $maHoaDon = $request->input('ma_hoa_don');

        $hoaDon = HoaDon::where('ma_hoa_don', $maHoaDon)->first();
        if (!$hoaDon) {
            return response()->json(['error' => 'Hóa đơn không tồn tại'], 404);
        }

        $idHoaDon = $hoaDon->id;

        // Lấy danh sách bàn từ hóa đơn
        $idBans = HoaDonBan::where('hoa_don_id', $idHoaDon)->pluck('ban_an_id');
        $bansConLai = BanAn::whereNotIn('id', $idBans)->get();

        // Lấy danh sách món ăn kèm số lượng
        $chiTietHoaDon = ChiTietHoaDon::where('hoa_don_id', $idHoaDon)
            ->select('mon_an_id', 'so_luong')
            ->get();

        $monAnIds = $chiTietHoaDon->pluck('mon_an_id');
        $monAn = MonAn::whereIn('id', $monAnIds)->get()->keyBy('id'); // Lưu danh sách món theo ID

        // Gộp danh sách món ăn với số lượng
        $danhSachMon = $chiTietHoaDon->map(function ($item) use ($monAn) {
            return [
                'id_mon' => $item->mon_an_id,
                'ten_mon' => $monAn[$item->mon_an_id]->ten ?? 'Không xác định',
                'so_luong' => $item->so_luong
            ];
        });

        return response()->json([
            'data' => $bansConLai,
            'mon_an' => $danhSachMon,
        ]);
    }

    private function generateMaHoaDon()
    {
        do {
            // Lấy ngày hiện tại theo định dạng YYYYMMDD
            $date = date('Ymd');

            // Tạo một số ngẫu nhiên có 4 chữ số từ uniqid()
            $randomNumber = strtoupper(uniqid());

            // Ghép lại thành mã hóa đơn
            $maHoaDon = 'HD-' . $date . '-' . substr($randomNumber, -4); // Chỉ lấy 4 ký tự cuối

            // Kiểm tra xem mã này đã tồn tại trong bảng hoa_dons chưa
            $exists = HoaDon::where('ma_hoa_don', $maHoaDon)->exists();
        } while ($exists); // Nếu tồn tại, tiếp tục tạo mã mới

        return $maHoaDon;
    }


    public function tachDon(Request $request)
    {
        $maHoaDon = $request->input('ma_hoa_don');
        $banAnMoiIds = $request->input('ban_moi_id');
        $monTach = $request->input('mon_tach');

        // Generate mã hóa đơn mới
        $maHoaDonMoi = $this->generateMaHoaDon();

        // Tạo hóa đơn mới
        $newHoaDon = HoaDon::create([
            'ma_hoa_don' => $maHoaDonMoi,
            'khach_hang_id' => 0,
            'phuong_thuc_thanh_toan' => 'tien_mat',
            'tong_tien' => 0
        ]);

        // Gán bàn mới vào hóa đơn
        $banAnMoiIds = is_array($banAnMoiIds) ? $banAnMoiIds : [$banAnMoiIds];

        foreach ($banAnMoiIds as $banAnId) {
            HoaDonBan::create([
                'hoa_don_id' => $newHoaDon->id,
                'ban_an_id' => $banAnId,
                'trang_thai' => 'dang_xu_ly'
            ]);
        }

        // Lấy danh sách ID món và thông tin món ăn
        $monAnIds = collect($monTach)->pluck('id_mon')->toArray();
        $monAnList = MonAn::whereIn('id', $monAnIds)->get()->keyBy('id');

        // Lưu chi tiết món ăn và tính tổng tiền
        $tongTien = 0;

        foreach ($monTach as $mon) {
            $idMon = $mon['id_mon'];
            $soLuong = $mon['so_luong_tach'];

            if (!isset($monAnList[$idMon])) {
                return response()->json(['error' => "Không tìm thấy món ăn với ID: $idMon"], 404);
            }

            $donGia = $monAnList[$idMon]->gia;
            $thanhTien = $donGia * $soLuong;
            $tongTien += $thanhTien;

            ChiTietHoaDon::create([
                'hoa_don_id' => $newHoaDon->id,
                'mon_an_id' => $idMon,
                'so_luong' => $soLuong,
                'don_gia' => $donGia,
                'thanh_tien' => $thanhTien
            ]);
        }

        // Cập nhật tổng tiền vào hóa đơn
        $newHoaDon->update([
            'tong_tien' => $tongTien
        ]);

        return response()->json([
            'ma_hoa_don' => $maHoaDonMoi,
            'ban_an_id' => $banAnMoiIds,
            'tong_tien' => $tongTien,
            'mon_tach' => $monTach
        ]);
    }
}
