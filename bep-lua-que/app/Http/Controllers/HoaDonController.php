<?php

namespace App\Http\Controllers;

use App\Events\HoaDonAdded;
use App\Events\HoaDonUpdated;
use App\Models\HoaDon;
use App\Http\Requests\StoreHoaDonRequest;
use App\Http\Requests\UpdateHoaDonRequest;
use App\Models\BanAn;
use App\Models\ChiTietHoaDon;
use App\Models\DatBan;
use App\Models\HoaDonBan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HoaDonController extends Controller
{
    public function index(Request $request)
    {
        $query = HoaDon::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('ma_hoa_don', 'like', '%' . $request->search . '%')
                ->orWhere('khach_hang_id', 'like', '%' . $request->search . '%');
        }

        $hoa_don = $query->latest('id')->paginate(10);

        // Nếu là Ajax request, trả về HTML của bảng luôn
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.hoadon.index', compact('hoa_don'))->render(),
            ]);
        }

        return view('admin.hoadon.index', compact('hoa_don'));
    }


    private function generateMaHoaDon()
    {
        // Lấy ngày hiện tại theo định dạng YYYYMMDD
        $date = date('Ymd');

        // Tạo một số ngẫu nhiên có 4 chữ số
        $randomNumber = strtoupper(uniqid()); // Dùng uniqid để tạo một chuỗi ngẫu nhiên

        // Ghép lại thành mã hóa đơn
        $maHoaDon = 'HD-' . $date . '-' . substr($randomNumber, -4); // Chỉ lấy 4 ký tự cuối

        return $maHoaDon;
    }

    public function createHoaDon(Request $request)
    {
        $banAnId = $request->input('ban_an_id'); // ID bàn ăn
        $monAnId = $request->input('mon_an_id'); // ID món ăn
        $giaMon = $request->input('gia'); // Giá món ăn

        if (!$banAnId || !$monAnId || !$giaMon) {
            return response()->json(['error' => 'Thiếu thông tin đầu vào!'], 400);
        }

        // Kiểm tra xem bàn này đã có hóa đơn nào chưa thanh toán hay không
        $hoaDonBan = HoaDonBan::where('ban_an_id', $banAnId)
            ->where('trang_thai', 'dang_xu_ly')
            ->first();

        if ($hoaDonBan) {
            // Nếu đã có hóa đơn đang xử lý, lấy hóa đơn đó
            $hoaDon = HoaDon::find($hoaDonBan->hoa_don_id);
        } else {
            // Nếu chưa có hóa đơn, tạo mới
            $hoaDon = HoaDon::create([
                'ma_hoa_don' => $this->generateMaHoaDon(),
                'khach_hang_id' => 0,
                'tong_tien' => 0.00,
                'phuong_thuc_thanh_toan' => 'tien_mat',
                'mo_ta' => null
            ]);

            // Liên kết hóa đơn với bàn ăn (trạng thái `dang_xu_ly`)
            $hoaDonBan = HoaDonBan::create([
                'hoa_don_id' => $hoaDon->id,
                'ban_an_id' => $banAnId,
                'trang_thai' => 'dang_xu_ly'
            ]);
        }


        // Kiểm tra xem món ăn đã có trong hóa đơn chưa
        $chiTietHoaDon = ChiTietHoaDon::where('hoa_don_id', $hoaDon->id)
            ->where('mon_an_id', $monAnId)
            ->first();

        if ($chiTietHoaDon) {
            // Nếu món ăn đã có, tăng số lượng
            $chiTietHoaDon->increment('so_luong');
            $chiTietHoaDon->increment('thanh_tien', $giaMon);
        } else {
            // Nếu chưa có, thêm mới vào bảng chi tiết hóa đơn
            ChiTietHoaDon::create([
                'hoa_don_id' => $hoaDon->id,
                'mon_an_id' => $monAnId,
                'so_luong' => 1,
                'don_gia' => $giaMon,
                'thanh_tien' => $giaMon,
                'trang_thai' => 'cho_xac_nhan'
            ]);
            $hoaDon = HoaDon::with('chiTietHoaDons')->find($hoaDon->id);
            event(new HoaDonAdded($hoaDon));
        }
        // Cập nhật tổng tiền trong bảng `hoa_don`
        $tongTien = ChiTietHoaDon::where('hoa_don_id', $hoaDon->id)->sum('thanh_tien');
        $hoaDon->update(['tong_tien' => $tongTien]);

        // 🔥 Nếu hóa đơn có món ăn, đổi trạng thái bàn thành "co_khach"
        $soLuongMon = ChiTietHoaDon::where('hoa_don_id', $hoaDon->id)->count();
        if ($soLuongMon > 0) {
            BanAn::where('id', $banAnId)->update(['trang_thai' => 'co_khach']);
        }

        // Lấy danh sách các đặt bàn của bàn ăn đó
        $datBan = DatBan::where('ban_an_id', $banAnId)
            ->whereIn('trang_thai', ['dang_xu_ly', 'xac_nhan'])
            ->exists(); // Kiểm tra xem có bản ghi nào không

        // Nếu không có đặt bàn nào đang xử lý hoặc xác nhận, thì tạo mới
        if (!$datBan) {
            $maDatBan = DatBan::generateMaDatBan();

            DatBan::create([
                'ban_an_id' => $banAnId,
                'khach_hang_id' => 0, // Nếu không có khách hàng thì để null
                'so_dien_thoai' => '0', // Nếu không có số điện thoại thì để null
                'gio_du_kien' => Carbon::now(),
                'thoi_gian_den' => Carbon::now(),
                'so_nguoi' => 1, // Mặc định là 1 người
                'trang_thai' => 'xac_nhan',
                'ma_dat_ban' => $maDatBan,
                'mo_ta' => null,
            ]);
        }


        // Nạp luôn chi tiết hóa đơn để gửi đầy đủ dữ liệu
        $hoaDon = HoaDon::with('chiTietHoaDons')->find($hoaDon->id);

        event(new HoaDonUpdated($hoaDon));

        return response()->json([
            'data' => $hoaDon
        ], 200);
    }


    public function show($id)
    {
        $hoaDon = HoaDon::with(['chiTietHoaDons.monAn', 'banAns'])->findOrFail($id);

        return view('admin.hoadon.show', compact('hoaDon'));
    }
}
