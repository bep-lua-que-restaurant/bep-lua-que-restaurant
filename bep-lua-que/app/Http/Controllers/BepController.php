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



    // public function themMonAnVaoBep(Request $request)
    // {
    //     $monAn = MonAn::create([
    //         'ten' => $request->ten,
    //         'so_luong' => $request->so_luong,
    //         'hoa_don_id' => $request->hoa_don_id
    //     ]);

    //     broadcast(new MonMoiDuocThem($monAn));

    //     return response()->json(['success' => true, 'monAn' => $monAn]);
    // }

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

        // Tìm bản ghi ChiTietHoaDon với các quan hệ
        $mon = ChiTietHoaDon::with(['monAn', 'hoaDon.hoaDonBan.banAn'])
            ->find($id);

        if (!$mon) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy món ăn.'], 404);
        }

        // Lấy danh sách các món ăn khác trong cùng hóa đơn, trùng mon_an_id và trạng thái mới
        $trangThaiMoi = $request->trang_thai;
        $duplicateMons = ChiTietHoaDon::where('hoa_don_id', $mon->hoa_don_id)
            ->where('mon_an_id', $mon->mon_an_id)
            ->where('trang_thai', $trangThaiMoi)
            ->where('id', '!=', $id)
            ->get();

        if ($duplicateMons->isNotEmpty()) {
            // Nếu có bản ghi trùng, gộp số lượng và thành tiền
            $totalSoLuong = $mon->so_luong + $duplicateMons->sum('so_luong');
            $totalThanhTien = $mon->thanh_tien + $duplicateMons->sum('thanh_tien');

            // Cập nhật bản ghi hiện tại
            $mon->so_luong = $totalSoLuong;
            $mon->thanh_tien = $totalThanhTien;
            $mon->trang_thai = $trangThaiMoi;
            $mon->updated_at = now();
            $mon->save();

            // Xóa các bản ghi trùng lặp
            foreach ($duplicateMons as $duplicate) {
                $duplicate->forceDelete();
            }
        } else {
            // Nếu không có trùng lặp, chỉ cập nhật trạng thái
            $mon->trang_thai = $trangThaiMoi;
            $mon->updated_at = now();
            $mon->save();
        }

        // Gửi sự kiện
        event(new TrangThaiCapNhat($mon));

        // Lấy tên món và tên bàn
        $tenMon = $mon->monAn ? $mon->monAn->ten_mon : 'Không xác định'; // Tên món
        $tenBan = $mon->hoaDon && $mon->hoaDon->hoaDonBan && $mon->hoaDon->hoaDonBan->banAn
            ? $mon->hoaDon->hoaDonBan->banAn->ten_ban
            : 'Không xác định'; // Tên bàn

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công.',
            'mon' => $mon,
            'ten_mon' => $tenMon,
            'ten_ban' => $tenBan
        ]);
    }
}
