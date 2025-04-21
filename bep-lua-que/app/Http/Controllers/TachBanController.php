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

        // Lấy danh sách món ăn kèm số lượng và trạng thái
        $chiTietHoaDon = ChiTietHoaDon::where('hoa_don_id', $idHoaDon)
            ->select('mon_an_id', 'so_luong', 'trang_thai')
            ->get();

        $monAnIds = $chiTietHoaDon->pluck('mon_an_id');
        $monAn = MonAn::whereIn('id', $monAnIds)->get()->keyBy('id');

        // Gộp danh sách món ăn với số lượng và trạng thái
        $danhSachMon = $chiTietHoaDon->map(function ($item) use ($monAn) {
            return [
                'id_mon' => $item->mon_an_id,
                'ten_mon' => $monAn[$item->mon_an_id]->ten ?? 'Không xác định',
                'so_luong' => $item->so_luong,
                'trang_thai' => $item->trang_thai
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
            $date = date('Ymd');
            $randomNumber = strtoupper(uniqid());
            $maHoaDon = 'HD-' . $date . '-' . substr($randomNumber, -4);
            $exists = HoaDon::where('ma_hoa_don', $maHoaDon)->exists();
        } while ($exists);

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
                'tong_tien' => 0,
                'tong_tien_truoc_khi_giam' => 0
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

                // Lấy chi tiết hóa đơn gốc để lấy trạng thái
                $chiTietMonAn = ChiTietHoaDon::where('hoa_don_id', $hoaDonGoc->id)
                    ->where('mon_an_id', $idMon)
                    ->first();

                if (!$chiTietMonAn) {
                    throw new \Exception("Không tìm thấy chi tiết hóa đơn cho món ăn với ID: $idMon");
                }

                $donGia = $monAnList[$idMon]->gia;
                $thanhTien = $donGia * $soLuongTach;
                $tongTienMoi += $thanhTien;

                // Tạo chi tiết hóa đơn mới với trạng thái từ hóa đơn gốc
                ChiTietHoaDon::create([
                    'hoa_don_id' => $newHoaDon->id,
                    'mon_an_id' => $idMon,
                    'so_luong' => $soLuongTach,
                    'don_gia' => $donGia,
                    'thanh_tien' => $thanhTien,
                    'trang_thai' => $chiTietMonAn->trang_thai // Gán trạng thái từ hóa đơn gốc
                ]);
            }

            $newHoaDon->update([
                'tong_tien' => $tongTienMoi,
                'tong_tien_truoc_khi_giam' => $tongTienMoi
            ]);

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
            $hoaDonGoc->update([
                'tong_tien' => $tongTienGoc,
                'tong_tien_truoc_khi_giam' => $tongTienGoc
            ]);

            if ($tongTienGoc == 0) {
                return response()->json([
                    'xac_nhan_xoa' => true,
                    'hoa_don_goc' => [
                        'ma_hoa_don' => $hoaDonGoc->ma_hoa_don,
                        'tong_tien' => $hoaDonGoc->tong_tien,
                        'tong_tien_truoc_khi_giam' => $hoaDonGoc->tong_tien_truoc_khi_giam
                    ]
                ]);
            }

            return response()->json([
                'message' => 'Success',
                'hoa_don_goc_id' => $hoaDonGoc->id,
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
            $banIds = HoaDonBan::where('hoa_don_id', $hoaDon->id)->pluck('ban_an_id')->toArray();
            BanAn::whereIn('id', $banIds)->update(['trang_thai' => 'trong']);
            foreach ($banIds as $banId) {
                event(new BanAnUpdated(BanAn::find($banId)));
            }
            $hoaDon->forceDelete();
        }
        $hoaDon->forceDelete();
    }
}