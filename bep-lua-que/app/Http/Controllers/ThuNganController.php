<?php

namespace App\Http\Controllers;

use App\Models\BanAn;
use App\Models\MonAn;
use App\Models\DatBan;
use App\Models\HoaDon;
use App\Models\HoaDonBan;
use App\Models\KhachHang;
use App\Events\BanAnUpdated;
use Illuminate\Http\Request;
use App\Events\HoaDonUpdated;
use App\Models\ChiTietHoaDon;
use App\Events\MonMoiDuocThem;

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

    public function getBanDeGhep()
    {
        $banAn = BanAn::whereNull('deleted_at')->get(); // Lấy tất cả bàn ăn chưa bị xóa mềm
        return response()->json($banAn);
    }

    public function getBillBan($id)
    {
        // Lấy thông tin bàn ăn
        $ban = BanAn::find($id);

        if (!$ban) {
            return response()->json(['message' => 'Bàn không tồn tại'], 404);
        }

        // Nếu bàn trống, chưa có hóa đơn
        if ($ban->trang_thai === 'trong') {
            return response()->json([
                'bill' => [
                    'ten_ban' => $ban->ten_ban,
                    'ma_hoa_don' => 'Chưa có',
                    'tong_tien' => 0,
                    'tong_so_luong_mon_an' => 0,
                    'mon_an' => []
                ]
            ]);
        }

        // Tìm hóa đơn của bàn này
        $hoaDonBan = HoaDonBan::where('ban_an_id', $id)->first();

        if (!$hoaDonBan) {
            return response()->json([
                'bill' => [
                    'ten_ban' => $ban->ten_ban,
                    'ma_hoa_don' => 'Chưa có',
                    'tong_tien' => 0,
                    'tong_so_luong_mon_an' => 0,
                    'mon_an' => []
                ]
            ]);
        }

        // Lấy thông tin hóa đơn
        $hoaDon = HoaDon::find($hoaDonBan->hoa_don_id);

        if (!$hoaDon) {
            return response()->json([
                'bill' => [
                    'ten_ban' => $ban->ten_ban,
                    'ma_hoa_don' => 'Chưa có',
                    'tong_tien' => 0,
                    'tong_so_luong_mon_an' => 0,
                    'mon_an' => []
                ]
            ]);
        }

        // Lấy danh sách món ăn
        $monAn = ChiTietHoaDon::where('hoa_don_id', $hoaDon->id)
            ->join('mon_ans', 'chi_tiet_hoa_dons.mon_an_id', '=', 'mon_ans.id')
            ->select(
                'mon_ans.ten as ten_mon',
                'mon_ans.gia as gia_mon',
                'chi_tiet_hoa_dons.so_luong'
            )
            ->get();

        // Tính tổng số lượng món ăn
        $tongSoLuongMonAn = ChiTietHoaDon::where('hoa_don_id', $hoaDon->id)
            ->sum('so_luong');

        return response()->json([
            'bill' => [
                'ten_ban' => $ban->ten_ban,
                'ma_hoa_don' => $hoaDon->ma_hoa_don,
                'tong_tien' => $hoaDon->tong_tien,
                'tong_so_luong_mon_an' => $tongSoLuongMonAn, // Tổng số lượng món ăn
                'mon_an' => $monAn
            ]
        ]);
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


        $hoaDonBan = HoaDonBan::where('hoa_don_id', $hoaDon->id)->first();
        // Lấy số người từ bảng dat_bans thông qua ban_an_id
        if ($hoaDonBan) {
            // Lấy số người từ bảng dat_bans qua ban_an_id
            $soNguoi = DatBan::where('ban_an_id', $hoaDonBan->ban_an_id)->value('so_nguoi');
        } else {
            $soNguoi = 0; // Nếu không tìm thấy ban_an_id trong bảng hoa_don_ban
        }

        // Trả về chi tiết hóa đơn cùng với số người
        return response()->json([
            'chi_tiet_hoa_don' => $chiTietHoaDon,
            'so_nguoi' => $soNguoi
        ]);
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

    public function updateStatus(Request $request)
{
    $hoaDonId = $request->hoa_don_id;

    if (!$hoaDonId) {
        return response()->json(['success' => false, 'message' => 'Hóa đơn không hợp lệ.']);
    }

    // Cập nhật trạng thái món ăn
    $monAn = ChiTietHoaDon::where('hoa_don_id', $hoaDonId)
        ->where('trang_thai', 'cho_xac_nhan')
        ->first(); // Lấy 1 món ăn đầu tiên thỏa mãn điều kiện

    if (!$monAn) {
        return response()->json(['success' => false, 'message' => 'Món ăn không hợp lệ hoặc đã thay đổi trạng thái.']);
    }

    // Cập nhật trạng thái món ăn
    $monAn->update([
        'trang_thai' => 'cho_che_bien', // Hoặc trạng thái bạn muốn chuyển
        'updated_at' => now()
    ]);

    // Gửi sự kiện với thông tin món ăn đầy đủ
    event(new MonMoiDuocThem($monAn));

    return response()->json(['success' => true]);
}


    public function updateBanStatus(Request $request)
    {
        $banAnId = $request->input('ban_an_id');
        $khachHangId = $request->input('khach_hang_id');
        $soNguoi = $request->input('so_nguoi');
        if (!$banAnId) {
            return response()->json(['success' => false, 'message' => 'Bàn không hợp lệ.']);
        }

        // Tìm bàn theo ID
        $banAn = BanAn::find($banAnId);

        if (!$banAn) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy bàn ăn.']);
        }

        // Cập nhật trạng thái bàn
        $banAn->update([
            'trang_thai' => 'trong',
        ]);

        // Tìm hóa đơn bàn liên quan đến bàn này có trạng thái 'đang xử lý'
        $hoaDonBan = HoaDonBan::where('ban_an_id', $banAnId)
            ->where('trang_thai', 'dang_xu_ly')
            ->first();

        // Nếu `khach_hang_id` = 0, tạo khách mới
        if ($khachHangId == 0) {
            $khachHang = KhachHang::create([
                'ho_ten' => 'Khách lẻ',
                'email' => 'Chưa cập nhật',
                'dia_chi' => 'Chưa cập nhật',
                'so_dien_thoai' => 'Chưa cập nhật',
                'can_cuoc' => 'Chưa cập nhật',
            ]);
            $khachHangId = $khachHang->id; // Lấy ID khách vừa tạo
        }

        if ($hoaDonBan) {
            // Cập nhật trạng thái hóa đơn bàn thành 'đã thanh toán'
            $hoaDonBan->update([
                'trang_thai' => 'da_thanh_toan',
            ]);
        }

        $datBan = DatBan::where('ban_an_id', $banAnId)->where('trang_thai', 'dang_xu_ly')->first();

        if (!$datBan) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đặt bàn phù hợp.']);
        }

        // Cập nhật trạng thái đặt bàn thành "xac_nhan"
        $datBan->update([
            'trang_thai' => 'xac_nhan',
            'so_nguoi' => $soNguoi,
            'khach_hang_id' => $khachHangId ? $khachHangId : null, // Nếu không có, để null
        ]);
        // // Gửi sự kiện nếu cập nhật thành công
        event(new BanAnUpdated($banAn));

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công.']);
    }


    public function addCustomer(Request $request)
    {
        // Lưu khách hàng vào database
        $khachHang = KhachHang::create([
            'ho_ten'       => $request->input('name'),
            'email'        => $request->input('email'),
            'dia_chi'      => $request->input('address'),
            'so_dien_thoai' => $request->input('phone'),
            'can_cuoc'     => $request->input('cccd'),
        ]);

        // Trả về phản hồi JSON
        return response()->json([
            'success'      => true,
            'message'      => 'Thêm khách hàng thành công!',
            'customer_id'  => $khachHang->id,
            'customer_name' => $khachHang->ho_ten
        ]);
    }

    public function ghepBan(Request $request)
    {
        $idBanHienTai = $request->id_ban_hien_tai;
        $idBanMoi = $request->id_ban_moi;

        // Lấy ID hóa đơn của bàn hiện tại
        $hoaDonHienTai = HoaDonBan::where('ban_an_id', $idBanHienTai)
            ->where('trang_thai', 'dang_xu_ly')
            ->first();

        if (!$hoaDonHienTai) {
            return response()->json(['error' => 'Không tìm thấy hóa đơn!'], 404);
        }

        // Kiểm tra xem bàn mới đã có hóa đơn chưa
        $banMoiCoHoaDon = HoaDonBan::where('ban_an_id', $idBanMoi)
            ->where('trang_thai', 'dang_xu_ly')
            ->exists();

        if ($banMoiCoHoaDon) {
            return response()->json(['error' => 'Bàn này đã có hóa đơn!'], 400);
        }

        // Thêm bàn mới vào cùng hóa đơn
        HoaDonBan::create([
            'hoa_don_id' => $hoaDonHienTai->hoa_don_id,
            'ban_an_id' => $idBanMoi,
            'trang_thai' => 'dang_xu_ly'
        ]);

        // Cập nhật trạng thái bàn mới thành "có khách"
        BanAn::where('id', $idBanMoi)->update(['trang_thai' => 'co_khach']);

        return response()->json(['message' => 'Ghép bàn thành công!']);
    }
}
