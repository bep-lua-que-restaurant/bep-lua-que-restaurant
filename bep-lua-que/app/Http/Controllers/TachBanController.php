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
                'ten_mon' => $monAn[$item->mon_an_id]->ten ?? 'Không xác định',
                'so_luong' => $item->so_luong
            ];
        });
    
        return response()->json([
            'data' => $bansConLai,
            'mon_an' => $danhSachMon,
        ]);
    }
    
}
