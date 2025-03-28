<?php

namespace App\Http\Controllers;

use App\Events\BanAnUpdated;
use App\Models\BanAn;
use App\Models\ChiTietHoaDon;
use App\Models\DatBan;
use App\Models\HoaDon;
use App\Models\HoaDonBan;
use App\Models\MonAn;
use Carbon\Carbon;
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

        // Kiểm tra hóa đơn gốc
        $hoaDonGoc = HoaDon::where('ma_hoa_don', $maHoaDon)->first();
        if (!$hoaDonGoc) {
            return response()->json(['error' => "Không tìm thấy hóa đơn gốc với mã: $maHoaDon"], 404);
        }

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

        // Lưu chi tiết món ăn vào hóa đơn mới và tính tổng tiền
        $tongTienMoi = 0;

        foreach ($monTach as $mon) {
            $idMon = $mon['id_mon'];
            $soLuongTach = $mon['so_luong_tach'];

            if (!isset($monAnList[$idMon])) {
                return response()->json(['error' => "Không tìm thấy món ăn với ID: $idMon"], 404);
            }

            $donGia = $monAnList[$idMon]->gia;
            $thanhTien = $donGia * $soLuongTach;
            $tongTienMoi += $thanhTien;

            ChiTietHoaDon::create([
                'hoa_don_id' => $newHoaDon->id,
                'mon_an_id' => $idMon,
                'so_luong' => $soLuongTach,
                'don_gia' => $donGia,
                'thanh_tien' => $thanhTien
            ]);
        }

        // Cập nhật tổng tiền cho hóa đơn mới
        $newHoaDon->update(['tong_tien' => $tongTienMoi]);

        // Cập nhật số lượng món ăn trong hóa đơn gốc
        foreach ($monTach as $mon) {
            $idMon = $mon['id_mon'];
            $soLuongTach = $mon['so_luong_tach'];

            $chiTietMonAn = ChiTietHoaDon::where('hoa_don_id', $hoaDonGoc->id)
                ->where('mon_an_id', $idMon)
                ->first();

            if ($chiTietMonAn) {
                // Giảm số lượng món ăn
                $chiTietMonAn->so_luong -= $soLuongTach;

                // Nếu số lượng về 0 thì xóa luôn món ăn
                if ($chiTietMonAn->so_luong <= 0) {
                    $chiTietMonAn->delete();
                } else {
                    $chiTietMonAn->thanh_tien = $chiTietMonAn->so_luong * $chiTietMonAn->don_gia;
                    $chiTietMonAn->save();
                }
            }
        }

        // Cập nhật lại tổng tiền của hóa đơn gốc
        $tongTienGoc = ChiTietHoaDon::where('hoa_don_id', $hoaDonGoc->id)->sum('thanh_tien');
        $hoaDonGoc->update(['tong_tien' => $tongTienGoc]);

        // Lấy thông tin hóa đơn gốc sau khi cập nhật
        $hoaDonGocChiTiet = [
            'ma_hoa_don' => $hoaDonGoc->ma_hoa_don,
            'tong_tien' => $hoaDonGoc->tong_tien,
            'mon_an' => ChiTietHoaDon::where('hoa_don_id', $hoaDonGoc->id)->get()
        ];

        // Lấy thông tin hóa đơn mới
        $hoaDonMoiChiTiet = [
            'ma_hoa_don' => $newHoaDon->ma_hoa_don,
            'tong_tien' => $newHoaDon->tong_tien,
            'mon_an' => ChiTietHoaDon::where('hoa_don_id', $newHoaDon->id)->get()
        ];

        BanAn::whereIn('id', $banAnMoiIds)->update(['trang_thai' => 'co_khach']);

        // Lấy danh sách bàn đã cập nhật
        $banAnMoiList = BanAn::whereIn('id', $banAnMoiIds)->get();

        // Gửi sự kiện real-time cho từng bàn
        foreach ($banAnMoiList as $banAn) {
            event(new BanAnUpdated($banAn));
        }

        $maDatBanMoi = DatBan::generateMaDatBan();

        // Lưu đặt bàn vào bảng dat_ban
        foreach ($banAnMoiIds as $banAnId) {
            DatBan::create([
                'ma_dat_ban' => $maDatBanMoi,
                'khach_hang_id' => 0,
                'so_dien_thoai' => '0', // Nếu không có số điện thoại thì để null
                'so_nguoi' => 1, // Mặc định là 1 người
                'gio_du_kien' => Carbon::now(),
                'thoi_gian_den' => Carbon::now(),
                'hoa_don_id' => $newHoaDon->id,
                'ban_an_id' => $banAnId,
                'trang_thai' => 'xac_nhan', // hoặc trạng thái phù hợp
                'thoi_gian_dat' => now(), // Lưu thời gian đặt bàn hiện tại
                'mo_ta' => null,
            ]);
        }

        return response()->json([
            'hoa_don_goc' => $hoaDonGocChiTiet,
            'hoa_don_moi' => $hoaDonMoiChiTiet
        ]);
    }
}
