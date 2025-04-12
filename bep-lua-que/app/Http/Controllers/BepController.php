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
        $monAns = [];

        foreach ($request->mon_an as $mon) {
            $monAns[] = MonAn::create([
                'ten' => $mon['ten'],
                'so_luong' => $mon['so_luong'],
                'hoa_don_id' => $request->hoa_don_id
            ]);
        }

        // Gửi tất cả món một lần
        broadcast(new MonMoiDuocThem($monAns));

        return response()->json(['success' => true, 'monAns' => $monAns]);
    }


    public function updateTrangThai(Request $request, $id)
    {
        $request->validate([
            'trang_thai' => 'required|in:cho_che_bien,dang_nau,hoan_thanh'
        ]);
    
        $mon = ChiTietHoaDon::with(['monAn', 'hoaDon.hoaDonBan.banAn'])->find($id);
    
        if (!$mon) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy món ăn.'], 404);
        }
    
        $trangThaiHienTai = $mon->trang_thai;
        $thoiGianNau = $mon->monAn ? $mon->monAn->thoi_gian_nau : 0;
    
        switch ($trangThaiHienTai) {
            case 'cho_che_bien':
                $trangThaiMoi = 'dang_nau';
                $mon->thoi_gian_bat_dau_nau = now();
                $mon->thoi_gian_hoan_thanh_du_kien = now()->addMinutes($thoiGianNau);
                break;
    
            case 'dang_nau':
                $trangThaiMoi = 'hoan_thanh';
                $mon->thoi_gian_hoan_thanh_thuc_te = now();
                break;
    
            default:
                return response()->json(['success' => false, 'message' => 'Không thể thay đổi trạng thái món ăn này.'], 400);
        }
    
        // 🔍 Tìm bản ghi trùng để gộp (cùng món, cùng hóa đơn, cùng trạng thái mới)
        $monTrung = ChiTietHoaDon::where('id', '!=', $mon->id)
            ->where('hoa_don_id', $mon->hoa_don_id)
            ->where('mon_an_id', $mon->mon_an_id)
            ->where('trang_thai', $trangThaiMoi)
            ->first();
    
        if ($monTrung) {
            // Gộp số lượng vào món trùng
            $monTrung->so_luong += $mon->so_luong;
            $monTrung->save();
    
            // Xoá món hiện tại vì đã gộp
            $mon->forceDelete();
    
            return response()->json([
                'success' => true,
                'message' => 'Món ăn đã được gộp vào món cùng trạng thái.',
                'mon' => $monTrung,
                'ten_mon' => $monTrung->monAn->ten_mon ?? 'Không xác định',
                'ten_ban' => $monTrung->hoaDon->hoaDonBan->banAn->ten_ban ?? 'Không xác định'
            ]);
        } else {
            // Không có món trùng, cập nhật trạng thái như thường
            $mon->trang_thai = $trangThaiMoi;
            $mon->updated_at = now();
            $mon->save();
    
            // Gửi event nếu cần
            event(new TrangThaiCapNhat($mon));
    
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công.',
                'mon' => $mon,
                'ten_mon' => $mon->monAn->ten_mon ?? 'Không xác định',
                'ten_ban' => $mon->hoaDon->hoaDonBan->banAn->ten_ban ?? 'Không xác định'
            ]);
        }
    }
    
}
