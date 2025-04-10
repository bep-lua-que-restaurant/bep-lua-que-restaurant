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

        // Tìm bản ghi ChiTietHoaDon với các quan hệ
        $mon = ChiTietHoaDon::with(['monAn', 'hoaDon.hoaDonBan.banAn'])
            ->find($id);

        if (!$mon) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy món ăn.'], 404);
        }

        $thoiGianNau = $mon->monAn ? $mon->monAn->thoi_gian_nau : 0; // Thời gian nấu, giả sử là tính bằng phút

        // Kiểm tra trạng thái hiện tại và cập nhật theo logic yêu cầu
        switch ($mon->trang_thai) {
            case 'cho_che_bien':
                // Nếu trạng thái hiện tại là 'chờ chế biến', đổi thành 'đang nấu'
                $trangThaiMoi = 'dang_nau';
                $mon->thoi_gian_bat_dau_nau = now();

                $thoiGianBatDau = now();
                $thoiGianHoanThanh = $thoiGianBatDau->addMinutes($thoiGianNau); // Thời gian hoàn thành

                // Cập nhật thời gian dự kiến hoàn thành
                $mon->thoi_gian_hoan_thanh_du_kien = $thoiGianHoanThanh;
                break;

            case 'dang_nau':
                // Nếu trạng thái hiện tại là 'đang nấu', đổi thành 'hoàn thành'
                $trangThaiMoi = 'hoan_thanh';

                // Lưu thời gian hoàn thành thực tế
                $mon->thoi_gian_hoan_thanh_thuc_te = now(); // Lưu thời gian hoàn thành thực tế
                break;

            default:
                // Nếu trạng thái hiện tại là 'hoàn thành' hoặc không hợp lệ, không thay đổi
                return response()->json(['success' => false, 'message' => 'Không thể thay đổi trạng thái món ăn này.'], 400);
        }

        // Cập nhật trạng thái mới
        $mon->trang_thai = $trangThaiMoi;
        $mon->updated_at = now();
        $mon->save();

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
