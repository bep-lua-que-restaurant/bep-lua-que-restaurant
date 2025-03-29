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
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($request) {
            $maHoaDon = $request->input('ma_hoa_don');
            $banAnMoiIds = $request->input('ban_moi_id');
            $monTach = $request->input('mon_tach');

            $hoaDonGoc = HoaDon::where('ma_hoa_don', $maHoaDon)->first();
            if (!$hoaDonGoc) {
                throw new \Exception("Không tìm thấy hóa đơn gốc với mã: $maHoaDon");
            }

            $maHoaDonMoi = $this->generateMaHoaDon();
            $newHoaDon = HoaDon::create([
                'ma_hoa_don' => $maHoaDonMoi,
                'khach_hang_id' => 0,
                'phuong_thuc_thanh_toan' => 'tien_mat',
                'tong_tien' => 0
            ]);

            $banAnMoiIds = is_array($banAnMoiIds) ? $banAnMoiIds : [$banAnMoiIds];

            foreach ($banAnMoiIds as $banAnId) {
                HoaDonBan::create([
                    'hoa_don_id' => $newHoaDon->id,
                    'ban_an_id' => $banAnId,
                    'trang_thai' => 'dang_xu_ly'
                ]);
            }

            $monAnIds = collect($monTach)->pluck('id_mon')->toArray();
            $monAnList = MonAn::whereIn('id', $monAnIds)->get()->keyBy('id');
            $tongTienMoi = 0;

            foreach ($monTach as $mon) {
                $idMon = $mon['id_mon'];
                $soLuongTach = $mon['so_luong_tach'];

                if (!isset($monAnList[$idMon])) {
                    throw new \Exception("Không tìm thấy món ăn với ID: $idMon");
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

            $newHoaDon->update(['tong_tien' => $tongTienMoi]);

            foreach ($monTach as $mon) {
                $idMon = $mon['id_mon'];
                $soLuongTach = $mon['so_luong_tach'];

                $chiTietMonAn = ChiTietHoaDon::where('hoa_don_id', $hoaDonGoc->id)
                    ->where('mon_an_id', $idMon)
                    ->first();

                if ($chiTietMonAn) {
                    $chiTietMonAn->so_luong -= $soLuongTach;
                    if ($chiTietMonAn->so_luong <= 0) {
                        $chiTietMonAn->delete();
                    } else {
                        $chiTietMonAn->thanh_tien = $chiTietMonAn->so_luong * $chiTietMonAn->don_gia;
                        $chiTietMonAn->save();
                    }
                }
            }

            BanAn::whereIn('id', $banAnMoiIds)->update(['trang_thai' => 'co_khach']);
            $banAnMoiList = BanAn::whereIn('id', $banAnMoiIds)->get();
            foreach ($banAnMoiList as $banAn) {
                event(new BanAnUpdated($banAn));
            }

            $maDatBanMoi = DatBan::generateMaDatBan();
            foreach ($banAnMoiIds as $banAnId) {
                DatBan::create([
                    'ma_dat_ban' => $maDatBanMoi,
                    'khach_hang_id' => 0,
                    'so_dien_thoai' => '0',
                    'so_nguoi' => 1,
                    'gio_du_kien' => Carbon::now(),
                    'thoi_gian_den' => Carbon::now(),
                    'hoa_don_id' => $newHoaDon->id,
                    'ban_an_id' => $banAnId,
                    'trang_thai' => 'xac_nhan',
                    'thoi_gian_dat' => now(),
                    'mo_ta' => null,
                ]);
            }

            $tongTienGoc = ChiTietHoaDon::where('hoa_don_id', $hoaDonGoc->id)->sum('thanh_tien');
            $hoaDonGoc->update(['tong_tien' => $tongTienGoc]);

            if ($tongTienGoc == 0) {
                return response()->json([
                    'xac_nhan_xoa' => true,
                    'hoa_don_goc' => [
                        'ma_hoa_don' => $hoaDonGoc->ma_hoa_don,
                        'tong_tien' => $hoaDonGoc->tong_tien,
                    ]
                ]);
            }

            return response()->json([
                'message' => 'Success'
            ]);
        });
    }

    public function xoaHoaDonGoc(Request $request)
    {
        $maHoaDon = $request->input('ma_hoa_don');

        $hoaDon = HoaDon::where('ma_hoa_don', $maHoaDon)->first();
        if (!$hoaDon) {
            return response()->json(['error' => 'Hóa đơn không tồn tại'], 404);
        }

        if ($hoaDon->tong_tien > 0) {
            return response()->json(['error' => 'Không thể xóa hóa đơn vì vẫn còn tiền'], 400);
        }

        $this->xoaHoaDonRong($hoaDon);

        return response()->json(['message' => 'Hóa đơn đã được xóa thành công']);
    }

    private function xoaHoaDonRong($hoaDon)
    {
        if ($hoaDon->tong_tien == 0) {
            // Lấy danh sách bàn từ hóa đơn
            $banIds = HoaDonBan::where('hoa_don_id', $hoaDon->id)->pluck('ban_an_id')->toArray();

            // Cập nhật trạng thái bàn về "trống"
            BanAn::whereIn('id', $banIds)->update(['trang_thai' => 'trong']);

            // Gửi sự kiện cập nhật bàn (nếu cần)
            foreach ($banIds as $banId) {
                event(new BanAnUpdated(BanAn::find($banId)));
            }

            // Xóa hóa đơn cũ
            $hoaDon->forceDelete();
        }
        $hoaDon->forceDelete();
    }
}
