<?php

namespace App\Http\Controllers;

use App\Events\HoaDonUpdated;
use App\Models\BanAn;
use App\Models\ChiTietHoaDon;
use App\Models\MonAn;
use App\Models\HoaDon;
use App\Models\HoaDonBan;

use Illuminate\Http\Request;

class ThuNganController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }


    public function getBanAn(Request  $request)
    {
        $query = BanAn::query();

        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten_ban', 'like', '%' . $request->ten . '%');
        }

        if ($request->has('statusFilter') && $request->statusFilter != '') {
            if ($request->statusFilter == 'trong') {
                // Lọc các bản ghi có trạng thái 'trong'
                $query->where('trang_thai', 'trong');
            } elseif ($request->statusFilter == 'co_khach') {
                // Lọc các bản ghi có trạng thái 'co_khach'
                $query->where('trang_thai', 'co_khach');
            } elseif ($request->statusFilter == 'da_dat_truoc') {
                // Lọc các bản ghi có trạng thái 'da_dat_truoc'
                $query->where('trang_thai', 'da_dat_truoc');
            }
        }


        $data = $query->latest('id')->get();
        $hoaDons = HoaDon::latest('id')->get();
        // Xử lý trả về khi yêu cầu là Ajax
        if ($request->ajax()) {
            return response()->json([
                'html' => view('gdnhanvien.thungan.body-list', compact('data'))->render(),
            ]);
        }

        return view('gdnhanvien.thungan.index', compact('data', 'hoaDons'));
    }

    public function getHoaDonId(Request $request)
    {
        $banAnId = $request->input('ban_an_id');

        // Tìm hóa đơn của bàn ăn có trạng thái "đang xử lý"
        $hoaDonBan = HoaDonBan::where('ban_an_id', $banAnId)
            ->where('trang_thai', 'dang_xu_ly')
            ->first();

        if ($hoaDonBan) {
            $hoaDon = HoaDon::find($hoaDonBan->hoa_don_id);

            // Phát sự kiện real-time để cập nhật hóa đơn trên giao diện
            event(new HoaDonUpdated($hoaDon));
        }

        return response()->json([
            'hoa_don_id' => $hoaDonBan ? $hoaDonBan->hoa_don_id : null
        ]);
    }


    public function getHoaDonDetails(Request $request)
    {
        $hoaDonId = $request->input('hoa_don_id');

        $hoaDon = HoaDon::find($hoaDonId);
        if (!$hoaDon) {
            return response()->json(['error' => 'Hóa đơn không tồn tại'], 404);
        }

        $chiTietHoaDon = ChiTietHoaDon::where('hoa_don_id', $hoaDonId)
            ->join('mon_ans', 'chi_tiet_hoa_dons.mon_an_id', '=', 'mon_ans.id')
            ->select('chi_tiet_hoa_dons.*', 'mon_ans.ten as tenMon', 'mon_ans.gia as don_gia')
            ->get();



        return response()->json($chiTietHoaDon);
    }


    public function getThucDon(Request  $request)
    {
        $query = MonAn::with('hinhAnhs');

        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten', 'like', '%' . $request->ten . '%');
        }

        if ($request->has('statusFilter') && $request->statusFilter != '') {
            if ($request->statusFilter == 'trong') {
                // Lọc các bản ghi có trạng thái 'trong'
                $query->where('trang_thai', 'trong');
            } elseif ($request->statusFilter == 'co_khach') {
                // Lọc các bản ghi có trạng thái 'co_khach'
                $query->where('trang_thai', 'co_khach');
            } elseif ($request->statusFilter == 'da_dat_truoc') {
                // Lọc các bản ghi có trạng thái 'da_dat_truoc'
                $query->where('trang_thai', 'da_dat_truoc');
            }
        }


        $data = $query->latest('id')->get();

        // Xử lý trả về khi yêu cầu là Ajax
        if ($request->ajax()) {
            return response()->json([
                'html' => view('gdnhanvien.thungan.body-list-menu', compact('data'))->render(),
            ]);
        }

        return view('gdnhanvien.thungan.index', [
            'data' => $data,
            'route' => route('thungan.getMonAn'), // URL route cho AJAX
            'tableId' => 'list-container', // ID của bảng
            'searchInputId' => 'search-name', // ID của ô tìm kiếm
        ]);
    }
    public function show(Request $request)
    {
        $banAnId = $request->get('id'); // Lấy ID bàn từ request
        $banAn = BanAn::find($banAnId);

        if ($banAn) {
            // Trả về dữ liệu dưới dạng JSON
            return response()->json([
                'ten_ban' => $banAn->ten_ban,
                'trang_thai' => $banAn->trang_thai
            ]);
        } else {
            return response()->json(['error' => 'Bàn không tồn tại.'], 404);
        }
    }

    public function xoaHoaDon($id)
    {
        $hoaDon = HoaDon::find($id);
        if ($hoaDon) {
            $hoaDon->forceDelete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Hóa đơn không tồn tại']);

    }
}
