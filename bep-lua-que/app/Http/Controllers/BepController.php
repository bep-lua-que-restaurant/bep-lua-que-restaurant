<?php

namespace App\Http\Controllers;

use App\Models\MonAn;
use Illuminate\Http\Request;
use App\Models\ChiTietHoaDon;
use App\Events\MonMoiDuocThem;
use App\Events\TrangThaiCapNhat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BepController extends Controller
{
    public function index()
    {
        // Lấy tất cả các món Chờ chế biến
        $monAnChoCheBien = ChiTietHoaDon::with(['monAn', 'hoaDon.banAns'])
    ->where('trang_thai', 'cho_che_bien')
    ->get();
    
        // Lấy các món theo kiểu tổng hợp (tính tổng số lượng của mỗi món từ tất cả hóa đơn)
        $monAnTheoMon = ChiTietHoaDon::with(['monAn'])
            ->where('trang_thai', 'cho_che_bien')
            ->select('mon_an_id', DB::raw('SUM(so_luong) as total_so_luong'))
            ->groupBy('mon_an_id')
            ->get();
    
        // Lấy danh sách món đang nấu (vẫn hiển thị theo món và bàn)
        $monAnDangNau = ChiTietHoaDon::with(['monAn', 'hoaDon.banAns'])
            ->where('trang_thai', 'dang_nau')
            ->get();
    
        // Dữ liệu cho view
        return view('gdnhanvien.bep.index', compact('monAnChoCheBien', 'monAnTheoMon', 'monAnDangNau'));
    }
    


    public function themMonAnVaoBep(Request $request)
    {
        $monAn = MonAn::create([
            'ten' => $request->ten,
            'so_luong' => $request->so_luong,
            'hoa_don_id' => $request->hoa_don_id
        ]);
    
        broadcast(new MonMoiDuocThem($monAn));
    
        return response()->json(['success' => true, 'monAn' => $monAn]);
    }

    public function updateTrangThai(Request $request, $id)
    {
        $request->validate([
            'trang_thai' => 'required|in:cho_che_bien,dang_nau,hoan_thanh'
        ]);

        $mon = ChiTietHoaDon::find($id);

        if (!$mon) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy món ăn.'], 404);
        }

        $mon->trang_thai = $request->trang_thai;
        $mon->save();

        broadcast(new TrangThaiCapNhat($mon))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công.',
            'mon' => $mon
        ]);
    }
}
