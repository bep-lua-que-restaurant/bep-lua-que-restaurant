<?php

namespace App\Http\Controllers;

use App\Models\MonAn;
use App\Models\HoaDonBan;
use Illuminate\Http\Request;
use App\Models\ChiTietHoaDon;
use App\Events\MonMoiDuocThem;
use App\Events\TrangThaiCapNhat;
use Illuminate\Support\Facades\Log;

class BepController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }


    public function index()
    {
        $monAnChoCheBien = ChiTietHoaDon::with(['monAn', 'hoaDon.banAns'])
            ->where('trang_thai', 'cho_che_bien')
            ->get();


        $monAnDangNau = ChiTietHoaDon::with(['monAn', 'hoaDon.banAns'])
            ->where('trang_thai', 'dang_nau')
            ->get();

        return view('gdnhanvien.bep.index', compact('monAnChoCheBien', 'monAnDangNau'));
    }

    public function themMon(Request $request)
    {
        Log::info('Thêm món từ Postman:', $request->all());
        $data = $request->validate([
            'hoa_don_id' => 'required|exists:hoa_dons,id',
            'mon_an_id' => 'required|exists:mon_ans,id',
            'so_luong' => 'required|integer|min:1'
        ]);

        $monAn = MonAn::findOrFail($data['mon_an_id']);
        $donGia = $monAn->gia;
        $thanhTien = $donGia * $data['so_luong'];

        // Tạo món ăn trong hóa đơn
        $mon = ChiTietHoaDon::create([
            'hoa_don_id' => $data['hoa_don_id'],
            'mon_an_id' => $data['mon_an_id'],
            'so_luong' => $data['so_luong'],
            'don_gia' => $donGia,
            'thanh_tien' => $thanhTien,
            'trang_thai' => 'cho_che_bien',
        ]);

        // Phát sự kiện để giao diện cập nhật real-time
        broadcast(new MonMoiDuocThem($mon))->toOthers();

        return response()->json(['message' => 'Thêm món thành công!', 'monAn' => $mon], 201);
    }



    public function updateTrangThai(Request $request, $id)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'trang_thai' => 'required|in:cho_che_bien,dang_nau,hoan_thanh'
        ]);

        // Tìm món ăn theo ID
        $mon = ChiTietHoaDon::find($id);

        // Nếu không tìm thấy món ăn
        if (!$mon) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy món ăn.'], 404);
        }

        // Cập nhật trạng thái món ăn
        $mon->trang_thai = $request->trang_thai;
        $mon->save();

        // Gửi sự kiện cập nhật giao diện bếp
        broadcast(new TrangThaiCapNhat($mon))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công.',
            'mon' => $mon
        ]);
    }
}
