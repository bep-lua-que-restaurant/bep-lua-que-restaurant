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
        // Lấy tất cả các món Chờ chế biến, kèm mã hóa đơn
        $monAnChoCheBien = ChiTietHoaDon::with([
            'monAn',
            'hoaDon' => function ($query) {
                $query->select('id', 'ma_hoa_don'); // Lấy ma_hoa_don từ bảng hoa_dons
            }
        ])
            ->where('trang_thai', 'cho_che_bien')
            ->get();

        // Lấy các món theo kiểu tổng hợp (tính tổng số lượng của mỗi món từ tất cả hóa đơn)
        $monAnTheoMon = ChiTietHoaDon::with(['monAn'])
            ->where('trang_thai', 'cho_che_bien')
            ->select('mon_an_id', DB::raw('SUM(so_luong) as total_so_luong'))
            ->groupBy('mon_an_id')
            ->get();

        // Lấy danh sách món đang nấu, kèm mã hóa đơn
        $monAnDangNau = ChiTietHoaDon::with([
            'monAn',
            'hoaDon' => function ($query) {
                $query->select('id', 'ma_hoa_don'); // Lấy ma_hoa_don từ bảng hoa_dons
            }
        ])
            ->where('trang_thai', 'dang_nau')
            ->get();

        // Dữ liệu cho view
        return view('gdnhanvien.bep.index', compact('monAnChoCheBien', 'monAnTheoMon', 'monAnDangNau'));
    }


    public function themMonAnVaoBep(Request $request)
    {
        $monAns = [];
        $hoaDonId = $request->hoa_don_id;
        foreach ($request->mon_an as $mon) {
            $monAns[] = MonAn::create([
                'ten' => $mon['ten'],
                'so_luong' => $mon['so_luong'],
                'hoa_don_id' => $hoaDonId
            ]);
        }

        // Gửi sự kiện với cả hoa_don_id và monAns
        broadcast(new MonMoiDuocThem([
            'hoa_don_id' => $hoaDonId,
            'monAns' => $monAns,
        ]));

        return response()->json(['success' => true, 'monAns' => $monAns]);
    }


    public function updateTrangThai(Request $request, $id)
    {
        $request->validate([
            'trang_thai' => 'required|in:cho_che_bien,dang_nau,hoan_thanh'
        ]);

        // Tìm món với quan hệ
        $mon = ChiTietHoaDon::with(['monAn', 'hoaDon.hoaDonBan.banAn'])->find($id);

        if (!$mon) {
            Log::error("Không tìm thấy món ăn với id: {$id}");
            return response()->json(['success' => false, 'message' => 'Không tìm thấy món ăn.'], 404);
        }

        // Kiểm tra quan hệ
        if (!$mon->monAn || !$mon->hoaDon || !$mon->hoaDon->hoaDonBan || !$mon->hoaDon->hoaDonBan->banAn) {
            Log::error("Dữ liệu quan hệ không đầy đủ cho món id: {$id}", $mon->toArray());
            return response()->json(['success' => false, 'message' => 'Dữ liệu món ăn không đầy đủ.'], 500);
        }

        $trangThaiHienTai = $mon->trang_thai;
        $thoiGianNau = $mon->monAn->thoi_gian_nau ?? 0;

        // Xác định trạng thái mới
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
                Log::error("Trạng thái không hợp lệ: {$trangThaiHienTai} cho món id: {$id}");
                return response()->json(['success' => false, 'message' => 'Không thể thay đổi trạng thái món ăn này.'], 400);
        }

        Log::info("Cập nhật trạng thái món id: {$id}, từ {$trangThaiHienTai} sang {$trangThaiMoi}");

        // Tìm bản ghi trùng
        $monTrung = ChiTietHoaDon::where('id', '!=', $mon->id)
            ->where('hoa_don_id', $mon->hoa_don_id)
            ->where('mon_an_id', $mon->mon_an_id)
            ->where('trang_thai', $trangThaiMoi)
            ->with(['monAn', 'hoaDon.hoaDonBan.banAn'])
            ->first();

        try {
            if ($monTrung) {
                // Kiểm tra quan hệ của $monTrung
                if (!$monTrung->monAn || !$monTrung->hoaDon || !$monTrung->hoaDon->hoaDonBan || !$monTrung->hoaDon->hoaDonBan->banAn) {
                    Log::error("Dữ liệu quan hệ không đầy đủ cho món trùng id: {$monTrung->id}", $monTrung->toArray());
                    return response()->json(['success' => false, 'message' => 'Dữ liệu món trùng không đầy đủ.'], 500);
                }

                // Gộp số lượng
                $monTrung->so_luong += $mon->so_luong;
                $monTrung->save();

                // Gửi sự kiện real-time trước khi xóa
                Log::info("Gộp món id: {$id} vào món id: {$monTrung->id}, số lượng mới: {$monTrung->so_luong}, trạng thái: {$trangThaiMoi}");
                Log::info("Dữ liệu sự kiện:", $monTrung->toArray());
                event(new TrangThaiCapNhat($monTrung));

                // Xóa món hiện tại
                $mon->forceDelete();

                return response()->json([
                    'success' => true,
                    'message' => 'Món ăn đã được gộp vào món cùng trạng thái.',
                    'mon' => $monTrung,
                    'ten_mon' => $monTrung->monAn->ten_mon ?? 'Không xác định',
                    'ten_ban' => $monTrung->hoaDon->hoaDonBan->banAn->ten_ban ?? 'Không xác định'
                ]);
            } else {
                // Cập nhật trạng thái
                $mon->trang_thai = $trangThaiMoi;
                $mon->updated_at = now();
                $mon->save();

                // Gửi sự kiện real-time
                Log::info("Cập nhật món id: {$id}, trạng thái: {$trangThaiMoi}, số lượng: {$mon->so_luong}");
                Log::info("Dữ liệu sự kiện:", $mon->toArray());
                event(new TrangThaiCapNhat($mon));

                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật trạng thái thành công.',
                    'mon' => $mon,
                    'ten_mon' => $mon->monAn->ten_mon ?? 'Không xác định',
                    'ten_ban' => $mon->hoaDon->hoaDonBan->banAn->ten_ban ?? 'Không xác định'
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Lỗi khi cập nhật trạng thái món id: {$id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Cập nhật thất bại: ' . $e->getMessage()], 500);
        }
    }
}
