<?php

namespace App\Http\Controllers;

use App\Models\BanAn;
use App\Models\DanhMucMonAn;
use App\Models\MonAn;
use App\Models\HoaDon;
use App\Models\HoaDonBan;
use App\Models\KhachHang;
use App\Events\BanAnUpdated;
use App\Events\DatBanUpdated;
use App\Events\GhepBanEvent;
use App\Models\PhongAn;
use Illuminate\Http\Request;
use App\Events\HoaDonUpdated;
use App\Events\MonMoiDuocThem;
use App\Events\XoaMonAn;
use App\Models\ChiTietHoaDon;
use App\Models\DatBan;
use App\Models\MaGiamGia;
use App\Models\MonBiHuy;
use App\Models\NguyenLieu;
use App\Models\NguyenLieuMonAn;
use PhpParser\Node\Expr\FuncCall;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ThuNganController extends Controller
{

    public function getBanAn(Request  $request)
    {
        $query = BanAn::query();

        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten_ban', 'like', '%' . $request->ten . '%');
        }

        // 🔥 Lọc theo trạng thái bàn ăn (statusFilter)
        if ($request->has('statusFilter') && $request->statusFilter != '') {
            if (in_array($request->statusFilter, ['trong', 'co_khach', 'da_dat_truoc'])) {
                $query->where('trang_thai', $request->statusFilter);
            }
        }

        // // 🔥 Lọc theo vị trí bàn ăn (vi_tri)
        // if ($request->has('vi_tri') && $request->vi_tri != '') {
        //     $query->where('vi_tri', $request->vi_tri);
        // }

        $data = $query->get();
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
        // Tìm bàn ăn theo ID
        $ban = BanAn::find($id);

        if (!$ban) {
            return response()->json(['message' => 'Bàn không tồn tại'], 404);
        }

        // Nếu bàn trống, trả về thông tin mặc định
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

        // Tìm hóa đơn mới nhất của bàn
        $hoaDonBan = HoaDonBan::where('ban_an_id', $id)->latest()->first();
        $soNguoi = DatBan::where('ban_an_id', $hoaDonBan->ban_an_id)
            ->where('trang_thai', 'xac_nhan')
            ->value('so_nguoi');

        if (!$hoaDonBan) {
            return response()->json([
                'bill' => [
                    'ten_ban' => $ban->ten_ban,
                    'ma_hoa_don' => 'Chưa có',
                    'tong_tien' => 0,
                    'tong_so_luong_mon_an' => 0,
                    'so_nguoi' => 0,
                    'mon_an' => []
                ]
            ]);
        }

        // Tìm hóa đơn từ bảng hóa đơn
        $hoaDon = HoaDon::find($hoaDonBan->hoa_don_id);

        if (!$hoaDon) {
            return response()->json([
                'bill' => [
                    'ten_ban' => $ban->ten_ban,
                    'ma_hoa_don' => 'Chưa có',
                    'tong_tien' => 0,
                    'tong_so_luong_mon_an' => 0,
                    'so_nguoi' => 0,
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
        $tongSoLuongMonAn = $monAn->sum('so_luong');

        return response()->json([
            'bill' => [
                'ten_ban' => $ban->ten_ban,
                'ma_hoa_don' => $hoaDon->ma_hoa_don,
                'tong_tien' => $hoaDon->tong_tien,
                'tong_so_luong_mon_an' => $tongSoLuongMonAn,
                'so_nguoi' => $soNguoi,
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

        return response()->json([
            'hoa_don_id' => $hoaDonBan ? $hoaDonBan->hoa_don_id : null
        ]);
    }


    public function getHoaDonDetails(Request $request)
    {
        $hoaDonId = $request->input('hoa_don_id');

        // Lấy hóa đơn
        $hoaDon = HoaDon::find($hoaDonId);
        if (!$hoaDon) {
            return response()->json(['error' => 'Hóa đơn không tồn tại'], 404);
        }

        // Lấy danh sách bàn liên quan đến hóa đơn
        $hoaDonBans = HoaDonBan::withTrashed()->where('hoa_don_id', $hoaDonId)->get();
        $banAnIds = $hoaDonBans->pluck('ban_an_id');

        // Đếm số bàn có trạng thái 'đang_xu_ly'
        $soBanDangXuLy = HoaDonBan::withTrashed()
            ->whereIn('hoa_don_id', function ($query) use ($banAnIds) {
                $query->select('hoa_don_id')->from('hoa_don_bans')->whereIn('ban_an_id', $banAnIds);
            })
            ->where('trang_thai', 'dang_xu_ly')
            ->count();

        $daGhep = $soBanDangXuLy >= 2;

        // Lấy chi tiết hóa đơn với đầy đủ dữ liệu từ `mon_ans` và `hoa_dons`
        $chiTietHoaDon = ChiTietHoaDon::where('hoa_don_id', $hoaDonId)
            ->with(['monAn:id,ten,gia', 'hoaDon:id,ma_hoa_don'])
            ->get()
            ->map(function ($chiTiet) {
                return [
                    'id' => $chiTiet->id,
                    'hoa_don_id' => $chiTiet->hoa_don_id,
                    'mon_an_id' => $chiTiet->monAn->id,
                    'tenMon' => $chiTiet->monAn->ten,
                    'don_gia' => $chiTiet->monAn->gia,
                    'trang_thai' => $chiTiet->trang_thai,
                    'so_luong' => $chiTiet->so_luong,
                    'tong_tien' => $chiTiet->so_luong * $chiTiet->monAn->gia, // Tính tổng tiền từng món
                    'ma_hoa_don' => $chiTiet->hoaDon->ma_hoa_don, // Lấy mã hóa đơn từ quan hệ
                    'ghi_chu' => $chiTiet->ghi_chu,
                ];
            });

        // Lấy tổng số người đặt bàn (gộp tất cả bàn)
        $soNguoi = DatBan::whereIn('ban_an_id', $banAnIds)
            ->where('trang_thai', 'xac_nhan')
            ->orderBy('created_at', 'desc')
            ->value('so_nguoi') ?? 0;

        // Lấy danh sách tên bàn
        $tenBanAn = BanAn::whereIn('id', $banAnIds)->pluck('ten_ban');

        return response()->json([
            'chi_tiet_hoa_don' => $chiTietHoaDon,
            'so_nguoi' => $soNguoi,
            'da_ghep' => $daGhep,
            'ten_ban_an' => $tenBanAn,
            'maHoaDon' => $hoaDon->ma_hoa_don,
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

        if ($request->has('danhMuc') && $request->danhMuc != '') {
            $query->where('danh_muc_id', $request->danhMuc);
        }

        $data = $query->latest('id')->get();
        $danhMucs = DanhMucMonAn::all();
        // Xử lý trả về khi yêu cầu là Ajax
        if ($request->ajax()) {
            return response()->json([
                'html' => view('gdnhanvien.thungan.body-list-menu', compact('data'))->render(),
            ]);
        }

        return view('gdnhanvien.thungan.index', [
            'data' => $data,
            'danhMucs' => $danhMucs,
            'route' => route('thungan.getMonAn'), // URL route cho AJAX
            'tableId' => 'list-container', // ID của bảng
            'searchInputId' => 'search-name', // ID của ô tìm kiếm
        ]);
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
        $maHoaDon = $request->ma_hoa_don;

        if (!$maHoaDon) {
            return response()->json(['success' => false, 'message' => 'Mã hóa đơn không hợp lệ.']);
        }

        // Tìm hóa đơn dựa trên ma_hoa_don
        $hoaDon = HoaDon::where('ma_hoa_don', $maHoaDon)->first();

        if (!$hoaDon) {
            return response()->json(['success' => false, 'message' => 'Hóa đơn không tồn tại.']);
        }

        $hoaDonId = $hoaDon->id;

        // Kiểm tra xem hóa đơn có món ăn nào không
        $totalMonAn = ChiTietHoaDon::where('hoa_don_id', $hoaDonId)->count();

        // Lấy danh sách món ăn ở trạng thái 'cho_xac_nhan'
        $monAnList = ChiTietHoaDon::where('hoa_don_id', $hoaDonId)
            ->where('trang_thai', 'cho_xac_nhan')
            ->get();

        if ($monAnList->isEmpty()) {
            if ($totalMonAn === 0) {
                return response()->json(['success' => false, 'message' => 'Hóa đơn chưa có món ăn nào.']);
            }
            return response()->json(['success' => false, 'message' => 'Có vẻ như hóa đơn chưa thay đổi gì, hãy thử thêm mới món ăn và thử lại.']);
        }

        foreach ($monAnList as $monAn) {
            $monAnTrung = ChiTietHoaDon::where('hoa_don_id', $hoaDonId)
                ->where('mon_an_id', $monAn->mon_an_id)
                ->where('trang_thai', 'cho_che_bien')
                ->first();

            if ($monAnTrung) {
                $monAnTrung->so_luong += $monAn->so_luong;
                $monAnTrung->updated_at = now();
                $monAnTrung->save();

                $monAn->forceDelete();
            } else {
                $monAn->update([
                    'trang_thai' => 'cho_che_bien',
                    'updated_at' => now()
                ]);
            }
        }

        // Gửi sự kiện sau khi cập nhật xong
        event(new MonMoiDuocThem(
            ChiTietHoaDon::where('hoa_don_id', $hoaDonId)
                ->where('trang_thai', 'cho_che_bien')
                ->get()
        ));

        // Tính lại tổng tiền
        $chiTietList = ChiTietHoaDon::where('hoa_don_id', $hoaDonId)->get();
        $tongTien = 0;

        foreach ($chiTietList as $chiTiet) {
            $monAn = MonAn::find($chiTiet->mon_an_id);
            if ($monAn) {
                $tongTien += $monAn->gia * $chiTiet->so_luong;
            }
        }

        HoaDon::where('id', $hoaDonId)->update([
            'tong_tien' => $tongTien,
            'tong_tien_truoc_khi_giam' => $tongTien,
            'updated_at' => now()
        ]);

        return response()->json(['success' => true]);
    }

    public function updateBanStatus(Request $request)
    {
        $banAnId = $request->input('ban_an_id');
        $khachHangId = $request->input('khach_hang_id');
        $chiTietThanhToan = $request->input('chi_tiet_thanh_toan');
        $phuongThucThanhToan = $request->input('phuong_thuc_thanh_toan');
        $ma_hoa_don_ban = $request->input('ma_hoa_don_cua_ban');
        $xoa_mon_cho = $request->input('xoa_mon_cho');

        if (!$banAnId) {
            return response()->json(['success' => false, 'message' => 'Bàn không hợp lệ.']);
        }

        // Tìm hóa đơn
        $hoaDonTheoMa = HoaDon::where('ma_hoa_don', $ma_hoa_don_ban)->first();
        if (!$hoaDonTheoMa) {
            return response()->json(['success' => false, 'message' => 'Hóa đơn không tồn tại.']);
        }

        // Tính tổng tiền gốc của hóa đơn trước khi xử lý xóa món
        $chiTietHoaDons = ChiTietHoaDon::where('hoa_don_id', $hoaDonTheoMa->id)
            ->with('monAn')
            ->get();
        $tongTienGoc = $chiTietHoaDons->sum(function ($chiTiet) {
            return $chiTiet->monAn ? $chiTiet->monAn->gia * $chiTiet->so_luong : 0;
        });

        // Xử lý xóa món ăn chờ
        if (is_array($xoa_mon_cho) && count($xoa_mon_cho) > 0) {
            $chiTietHoaDonsToDelete = ChiTietHoaDon::whereIn('id', $xoa_mon_cho)
                ->with('monAn')
                ->get();

            // Tính tổng giá tiền của các món bị xóa
            $totalDeletedPrice = $chiTietHoaDonsToDelete->sum(function ($chiTiet) {
                return $chiTiet->monAn ? $chiTiet->monAn->gia * $chiTiet->so_luong : 0;
            });

            // Cập nhật tổng tiền
            if ($totalDeletedPrice > 0) {
                $tongTienMoi = $hoaDonTheoMa->tong_tien - $totalDeletedPrice;
                $tongTienTruocKhiGiam = $hoaDonTheoMa->id_ma_giam
                    ? ($tongTienGoc - $totalDeletedPrice) // Có mã giảm giá: giữ tổng tiền gốc
                    : $tongTienMoi; // Không có mã giảm giá: bằng tổng tiền mới

                $hoaDonTheoMa->update([
                    'tong_tien' => $tongTienMoi,
                    'tong_tien_truoc_khi_giam' => $tongTienTruocKhiGiam,
                ]);
            }

            // Xóa các bản ghi ChiTietHoaDon
            ChiTietHoaDon::whereIn('id', $xoa_mon_cho)->delete(); // Sử dụng soft delete
        }

        // Lấy thông tin bàn và hóa đơn bàn
        $banAn = BanAn::find($banAnId);
        $banTheoHoaDon = HoaDonBan::where('hoa_don_id', $hoaDonTheoMa->id)->get();
        $banIds = $banTheoHoaDon->pluck('ban_an_id')->toArray();

        // Lấy mã đặt bàn
        $maDatBans = DatBan::whereIn('ban_an_id', $banIds)
            ->where('trang_thai', 'xac_nhan')
            ->pluck('ma_dat_ban')
            ->toArray();

        if (!$maDatBans) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy mã đặt bàn.']);
        }

        // Tìm hóa đơn bàn
        $hoaDonBan = HoaDonBan::where('ban_an_id', $banAnId)
            ->where('trang_thai', 'dang_xu_ly')
            ->first();

        if (!$hoaDonBan) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy hóa đơn bàn.']);
        }

        // Cập nhật thông tin thanh toán và kiểm tra mã giảm giá
        $tongTienTruocKhiGiam = $hoaDonTheoMa->id_ma_giam
            ? $tongTienGoc // Có mã giảm giá: giữ tổng tiền gốc
            : $hoaDonTheoMa->tong_tien; // Không có mã giảm giá: bằng tổng tiền

        $hoaDonTheoMa->update([
            'mo_ta' => $chiTietThanhToan,
            'phuong_thuc_thanh_toan' => $phuongThucThanhToan,
            'tong_tien_truoc_khi_giam' => $tongTienTruocKhiGiam,
        ]);

        // Cập nhật trạng thái bàn
        $dsBanCungHoaDon = HoaDonBan::withTrashed()
            ->where('hoa_don_id', $hoaDonBan->hoa_don_id)
            ->where('trang_thai', 'dang_xu_ly')
            ->pluck('ban_an_id');

        foreach ($dsBanCungHoaDon as $banId) {
            $banAn = BanAn::find($banId);
            if ($banAn) {
                $banAn->update(['trang_thai' => 'trong']);
                event(new BanAnUpdated($banAn));
            }
        }

        // Cập nhật trạng thái hóa đơn bàn
        HoaDonBan::where('hoa_don_id', $hoaDonBan->hoa_don_id)
            ->where('trang_thai', 'dang_xu_ly')
            ->update(['trang_thai' => 'da_thanh_toan']);

        // Xử lý khách hàng
        if ($khachHangId == 0) {
            $khachHang = KhachHang::create([
                'ho_ten' => 'Khách lẻ',
                'email' => 'Chưa cập nhật',
                'dia_chi' => 'Chưa cập nhật',
                'so_dien_thoai' => 'Chưa cập nhật',
                'can_cuoc' => 'Chưa cập nhật',
            ]);
            $khachHangId = $khachHang->id;
        }

        $khachHang = KhachHang::find($khachHangId);

        // Cập nhật trạng thái đặt bàn
        $updateDatBan = DatBan::whereIn('ma_dat_ban', $maDatBans)->get();
        foreach ($updateDatBan as $datBan) {
            $datBan->update([
                'trang_thai' => 'da_thanh_toan',
                'khach_hang_id' => $khachHangId ?: null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công.',
            'hoaDon' => $hoaDonTheoMa,
            'khachHang' => $khachHang,
        ]);
    }

    public function addCustomer(Request $request)
    {
        try {
            DB::beginTransaction(); // Bắt đầu transaction

            // Lưu khách hàng vào database
            $khachHang = KhachHang::create([
                'ho_ten'       =>  $request->input('name'),
                'email'        =>   $request->input('email'),
                'dia_chi'      =>   $request->input('address'),
                'so_dien_thoai' =>  $request->input('phone'),
            ]);

            DB::commit(); // Xác nhận transaction

            // Trả về phản hồi JSON
            return response()->json([
                'success'       => true,
                'message'       => 'Thêm khách hàng thành công!',
                'customer_id'   => $khachHang->id,
                'customer_name' => $khachHang->ho_ten
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Hoàn tác transaction nếu có lỗi

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm khách hàng: ' . $e->getMessage()
            ], 500);
        }
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

    public function ghepBan(Request $request)
    {
        $idBanHienTai = $request->id_ban_hien_tai;
        $idDanhSachBanMoi = json_decode($request->input('danh_sach_ban'), true);

        $datban = DatBan::where('ban_an_id', $idBanHienTai)
            ->where('trang_thai', 'xac_nhan')->get();

        $soNguoiHienTai = DatBan::where('ban_an_id', $idBanHienTai)
            ->where('trang_thai', 'xac_nhan')
            ->value('so_nguoi');

        $soNguoiDanhSachBanMoi = DatBan::whereIn('ban_an_id', $idDanhSachBanMoi)
            ->where('trang_thai', 'xac_nhan')
            ->sum('so_nguoi');

        $totalSoNguoi = $soNguoiHienTai + $soNguoiDanhSachBanMoi;
        foreach ($datban as $dat) {
            $dat->update([
                'so_nguoi' => $totalSoNguoi,
            ]);
        }

        $datbanDanhSachMoi = DatBan::whereIn('ban_an_id', $idDanhSachBanMoi)
            ->where('trang_thai', 'xac_nhan')
            ->get();

        $firstDatBan = $datban->first();
        if ($firstDatBan) {
            $maDatBanChung = $firstDatBan->ma_dat_ban;
            if ($maDatBanChung) {
                foreach ($datbanDanhSachMoi as $dat) {
                    $dat->update([
                        'so_nguoi' => $totalSoNguoi,
                        'ma_dat_ban' => $maDatBanChung
                    ]);
                }
            }
        }

        $hoaDonHienTai = HoaDonBan::where('ban_an_id', $idBanHienTai)
            ->where('trang_thai', 'dang_xu_ly')
            ->first();

        if (!$hoaDonHienTai) {
            $hoaDon = HoaDon::create([
                'ma_hoa_don' => $this->generateMaHoaDon(),
                'khach_hang_id' => 0,
                'tong_tien' => 0.00,
                'phuong_thuc_thanh_toan' => 'tien_mat',
                'mo_ta' => null
            ]);

            HoaDonBan::create([
                'hoa_don_id' => $hoaDon->id,
                'ban_an_id' => $idBanHienTai,
                'trang_thai' => 'dang_xu_ly'
            ]);

            $hoaDonHienTai = HoaDonBan::where('ban_an_id', $idBanHienTai)
                ->where('trang_thai', 'dang_xu_ly')
                ->first();

            BanAn::where('id', $idBanHienTai)->update(['trang_thai' => 'co_khach']);
            $banAn = BanAn::find($idBanHienTai);
            event(new BanAnUpdated($banAn));
        }

        $hoaDonHienTaiID = $hoaDonHienTai->hoa_don_id;
        $maHoaDonChung = HoaDon::where('id', $hoaDonHienTaiID)->value('ma_hoa_don');
        $monAns = [];
        $maHoaDonCu = [];
        $banAnIds = array_merge([$idBanHienTai], $idDanhSachBanMoi);

        // Lấy tất cả mã hóa đơn từ các bàn được ghép
        $hoaDonBans = HoaDonBan::whereIn('ban_an_id', $banAnIds)
            ->where('trang_thai', 'dang_xu_ly')
            ->with(['hoaDon'])
            ->get();

        foreach ($hoaDonBans as $hoaDonBan) {
            if ($hoaDonBan->hoa_don_id != $hoaDonHienTaiID && $hoaDonBan->hoaDon) {
                $maHoaDonCu[] = $hoaDonBan->hoaDon->ma_hoa_don;
            }
        }

        foreach ($idDanhSachBanMoi as $idBanMoi) {
            $hoaDonBanMoi = HoaDonBan::where('ban_an_id', $idBanMoi)
                ->where('trang_thai', 'dang_xu_ly')
                ->first();

            if ($hoaDonBanMoi) {
                $hoaDonMoiID = $hoaDonBanMoi->hoa_don_id;

                $chiTietMonAnMoi = ChiTietHoaDon::where('hoa_don_id', $hoaDonMoiID)->get();

                foreach ($chiTietMonAnMoi as $monMoi) {
                    $monAnCu = ChiTietHoaDon::where('hoa_don_id', $hoaDonHienTaiID)
                        ->where('mon_an_id', $monMoi->mon_an_id)
                        ->where('trang_thai', $monMoi->trang_thai)
                        ->first();

                    if ($monAnCu) {
                        $monAnCu->so_luong += $monMoi->so_luong;
                        $monAnCu->thanh_tien = $monAnCu->so_luong * $monAnCu->don_gia;
                        $monAnCu->save();
                        $monMoi->delete();
                    } else {
                        ChiTietHoaDon::create([
                            'hoa_don_id' => $hoaDonHienTaiID,
                            'mon_an_id' => $monMoi->mon_an_id,
                            'so_luong' => $monMoi->so_luong,
                            'don_gia' => $monMoi->don_gia,
                            'thanh_tien' => $monMoi->thanh_tien,
                            'trang_thai' => $monMoi->trang_thai
                        ]);
                        $monMoi->delete();
                    }
                }

                $chiTietHoaDon = ChiTietHoaDon::where('hoa_don_id', $hoaDonHienTaiID)->get();
                $tongTien = 0;

                foreach ($chiTietHoaDon as $chiTiet) {
                    $chiTiet->thanh_tien = $chiTiet->so_luong * $chiTiet->don_gia;
                    $tongTien += $chiTiet->thanh_tien;
                    $chiTiet->save();
                }

                HoaDon::where('id', $hoaDonHienTaiID)->update([
                    'tong_tien_truoc_khi_giam' => $tongTien
                ]);

                HoaDon::where('id', $hoaDonMoiID)->delete();

                HoaDonBan::where('ban_an_id', $idBanMoi)
                    ->update(['hoa_don_id' => $hoaDonHienTaiID]);
            } else {
                HoaDonBan::create([
                    'hoa_don_id' => $hoaDonHienTai->hoa_don_id,
                    'ban_an_id' => $idBanMoi,
                    'trang_thai' => 'dang_xu_ly'
                ]);
            }

            BanAn::where('id', $idBanMoi)->update(['trang_thai' => 'co_khach']);
            $banAn = BanAn::find($idBanMoi);
            event(new BanAnUpdated($banAn));
        }

        // Thu thập danh sách món ăn để gửi qua sự kiện
        $chiTietHoaDons = ChiTietHoaDon::where('hoa_don_id', $hoaDonHienTaiID)
            ->with(['monAn'])
            ->get();

            foreach ($chiTietHoaDons as $chiTiet) {
                // Kiểm tra kiểu dữ liệu của thoi_gian_hoan_thanh_du_kien
                $thoiGianHoanThanh = $chiTiet->thoi_gian_hoan_thanh_du_kien;
                if ($thoiGianHoanThanh) {
                    $thoiGianHoanThanh = is_string($thoiGianHoanThanh) ? $thoiGianHoanThanh : $thoiGianHoanThanh->toDateTimeString();
                } else {
                    $thoiGianHoanThanh = null;
                }
            
                $monAns[] = [
                    'id' => $chiTiet->id,
                    'mon_an_id' => $chiTiet->mon_an_id,
                    'ten' => $chiTiet->monAn->ten,
                    'so_luong' => $chiTiet->so_luong,
                    'thoi_gian_nau' => $chiTiet->monAn->thoi_gian_nau,
                    'ghi_chu' => $chiTiet->ghi_chu,
                    'trang_thai' => $chiTiet->trang_thai,
                    'thoi_gian_hoan_thanh_du_kien' => $thoiGianHoanThanh,
                    'ma_hoa_don' => $maHoaDonChung
                ];
            }

        // Phát sự kiện ghép bàn
        event(new GhepBanEvent($monAns, $maHoaDonChung, $maHoaDonCu));

        return response()->json([
            'message' => 'Ghép bàn thành công!',
            'ma_hoa_don' => $maHoaDonChung
        ]);
    }
    public function updateQuantity(Request $request)
    {
        $monAnId = $request->mon_an_id;
        $thayDoi = (int) $request->thay_doi;

        // Giả sử trạng thái 'chờ xác nhận' là chuỗi "cho_xac_nhan"
        $chiTietHoaDon = ChiTietHoaDon::where('id', $monAnId)->first();

        if (!$chiTietHoaDon) {
            return response()->json(['error' => 'Món ăn không tồn tại!'], 404);
        }

        // Nếu trạng thái là 'chờ xác nhận'
        if ($chiTietHoaDon->trang_thai === 'cho_xac_nhan') {

            if ($chiTietHoaDon->so_luong + $thayDoi < 1) {
                return response()->json(['error' => 'Số lượng tối thiểu là 1'], 400);
            }

            $chiTietHoaDon->so_luong += $thayDoi;
            $chiTietHoaDon->thanh_tien = $chiTietHoaDon->so_luong * $chiTietHoaDon->don_gia;
            $chiTietHoaDon->save();
        } else {
            // Tạo bản ghi mới y hệt, chỉ khác trạng thái và số lượng
            $newChiTiet = $chiTietHoaDon->replicate(); // sao chép tất cả cột trừ khóa chính
            $newChiTiet->so_luong = max(1, $thayDoi); // số lượng tối thiểu là 1
            $newChiTiet->thanh_tien = $newChiTiet->so_luong * $newChiTiet->don_gia;
            $newChiTiet->trang_thai = 'cho_xac_nhan';
            $newChiTiet->save();

            $chiTietHoaDon = $newChiTiet; // để phần dưới xử lý tiếp
        }

        $hoaDonId = $chiTietHoaDon->hoa_don_id;

        // Tính lại tổng tiền
        $tongTien = ChiTietHoaDon::where('hoa_don_id', $hoaDonId)
            ->get()
            ->map(fn($item) => $item->so_luong * $item->don_gia)
            ->sum();

        $hoaDon = HoaDon::find($hoaDonId);
        $hoaDon->update(['tong_tien' => $tongTien]);

        // Phát sự kiện
        $hoaDon->load('chiTietHoaDons');
        broadcast(new HoaDonUpdated($hoaDon))->toOthers();

        return response()->json([
            'success' => true,
            'hoa_don_id' => $hoaDonId,
            'tong_tien' => $tongTien,
            'so_luong' => $chiTietHoaDon->so_luong,
            'thanh_tien' => $chiTietHoaDon->thanh_tien
        ]);
    }

    //xóa món ăn
    public function deleteMonAn(Request $request)
    {
        // Lấy chi_tiet_hoa_don_id từ request
        $chiTietId = $request->mon_an_id; // Đây là id của chi_tiet_hoa_dons
        $lyDoHuy = $request->ly_do ?? 'Không rõ lý do';
        $forceDelete = $request->force_delete ?? false;
        $checkStatusOnly = $request->check_status_only ?? false;

        // 1. Lấy chi tiết hóa đơn
        $chiTiet = ChiTietHoaDon::find($chiTietId);
        if (!$chiTiet) {
            return response()->json(['error' => 'Chi tiết hóa đơn không tồn tại!'], 404);
        }

        // Lấy thông tin món ăn từ bảng mon_ans để dự phòng
        $monAn = MonAn::find($chiTiet->mon_an_id);
        if (!$monAn) {
            return response()->json(['error' => 'Món ăn không tồn tại!'], 404);
        }

        // 2. Lấy hóa đơn
        $hoaDon = HoaDon::find($chiTiet->hoa_don_id);
        if (!$hoaDon) {
            return response()->json(['error' => 'Hóa đơn không tồn tại!'], 404);
        }

        // 3. Nếu chỉ kiểm tra trạng thái (lần gọi đầu tiên)
        if ($checkStatusOnly) {
            return response()->json([
                'success' => true,
                'trang_thai' => $chiTiet->trang_thai,
                'message' => $chiTiet->trang_thai === 'dang_nau' ? 'Món này đang được nấu, bạn có chắc chắn muốn hủy không?' : ($chiTiet->trang_thai === 'hoan_thanh' ? 'Món này đã hoàn thành, bạn có chắc chắn muốn hủy không?' : 'Món này đang chờ xác nhận.'),
            ], 200);
        }

        // 4. Xử lý xóa món (khi người dùng đã xác nhận)
        if ($chiTiet->trang_thai === 'cho_xac_nhan' || $forceDelete) {
            // Lưu trạng thái ban đầu trước khi xóa
            $trangThaiBanDau = $chiTiet->trang_thai;

            // Gửi broadcast và xóa bản ghi
            broadcast(new XoaMonAn($chiTiet));
            $chiTiet->forceDelete();

            // Nếu trạng thái ban đầu không phải "cho_xac_nhan", lưu vào bảng mon_bi_huys
            if ($trangThaiBanDau !== 'cho_xac_nhan') {
                MonBiHuy::create([
                    'mon_an_id' => $chiTiet->mon_an_id,
                    'ly_do' => $lyDoHuy,
                    'so_luong' => $chiTiet->so_luong,
                    'ngay_huy' => Carbon::now(),
                ]);
            }
        } else {
            return response()->json([
                'requires_confirmation' => true,
                'trang_thai' => $chiTiet->trang_thai,
                'message' => $chiTiet->trang_thai === 'dang_nau' ? 'Món này đang được nấu, bạn có chắc chắn muốn hủy không?' : ($chiTiet->trang_thai === 'hoan_thanh' ? 'Món này đã hoàn thành, bạn có chắc chắn muốn hủy không?' : 'Món này đang chờ xác nhận.'),
            ], 200);
        }

        // 5. Cập nhật tổng tiền
        $tongTien = ChiTietHoaDon::where('hoa_don_id', $hoaDon->id)
            ->sum(DB::raw('so_luong * don_gia'));

        $hoaDon->update(['tong_tien' => $tongTien]);
        $hoaDon->load('chiTietHoaDons');

        broadcast(new HoaDonUpdated($hoaDon))->toOthers();

        return response()->json([
            'success' => true,
            'tong_tien' => $tongTien,
        ]);
    }

    public function getOrders(Request $request)
    {
        // Lấy tất cả mã đặt bàn có trạng thái 'dang_xu_ly'
        $maDatBanList = DatBan::where('trang_thai', 'dang_xu_ly')
            ->pluck('ma_dat_ban'); // Lấy danh sách tất cả mã đặt bàn có trạng thái 'dang_xu_ly'

        if ($maDatBanList->isEmpty()) {
            return response()->json([]); // Nếu không có dữ liệu -> trả về mảng rỗng
        }

        // Gộp danh sách bàn theo các mã đặt bàn liên quan
        $orders = DatBan::join('ban_ans', 'dat_bans.ban_an_id', '=', 'ban_ans.id')
            ->join('khach_hangs', 'dat_bans.khach_hang_id', '=', 'khach_hangs.id')
            ->whereIn('dat_bans.ma_dat_ban', $maDatBanList) // Tìm tất cả đơn có trạng thái 'dang_xu_ly'
            ->groupBy('dat_bans.ma_dat_ban', 'khach_hangs.ho_ten', 'dat_bans.so_nguoi', 'dat_bans.thoi_gian_den')
            ->selectRaw('
                dat_bans.ma_dat_ban, 
                khach_hangs.ho_ten, 
                dat_bans.so_nguoi, 
                dat_bans.thoi_gian_den, 
                GROUP_CONCAT(ban_ans.ten_ban ORDER BY ban_ans.ten_ban SEPARATOR ", ") as danh_sach_ban
            ')
            ->get();

        return response()->json($orders);
    }

    public function thongTinHoaDon(Request $request)
    {
        if (!$request->maHoaDon) {
            return response()->json(['error' => 'Không tìm thấy mã hóa đơn'], 404);
        }

        $maHoaDon = $request->maHoaDon;

        $hoaDon = HoaDon::where('ma_hoa_don', $maHoaDon)->first();
        if (!$hoaDon) {
            // Nếu không tìm thấy hóa đơn, trả về lỗi
            return response()->json(['error' => 'Không tìm thấy hóa đơn với mã này'], 404);
        }

        $banAn = HoaDonBan::where('hoa_don_id', $hoaDon->id)->get();
        // Kiểm tra xem có bàn ăn nào không
        if ($banAn->isEmpty()) {
            return response()->json(['error' => 'Không tìm thấy bàn ăn cho hóa đơn này'], 404);
        }

        $datBan = DatBan::where('ban_an_id', $banAn->first()->ban_an_id)->first();

        $khachHang = KhachHang::where('id', $datBan->khach_hang_id)->first();
        if (!$khachHang) {
            return response()->json([
                'banAn' => $banAn,
                'khachHang' => null
            ]);
        }

        return response()->json([
            'banAn' => $banAn,
            'khachHang' => $khachHang
        ]);
    }

    public function saveSoNguoi(Request $request)
    {
        $banId = $request->input('banId');
        $soNguoi = $request->input('soNguoi');

        $banAn = BanAn::find($banId);
        if (!$banAn) {
            return response()->json(['error' => 'Không tìm thấy bàn ��n'], 404);
        }

        // Lấy mã đặt bàn từ bảng DatBan
        $maDatBan = DatBan::where('ban_an_id', $banAn->id)
            ->where('trang_thai', 'xac_nhan')
            ->value('ma_dat_ban');
        if (!$maDatBan) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy mã đặt bàn.']);
        }

        // Lấy danh sách tất cả các bản ghi trong bảng DatBan có mã đặt bàn này và có trạng thái "xác nhận"
        $datBans = DatBan::where('ma_dat_ban', $maDatBan)
            ->where('trang_thai', 'xac_nhan')  // Trạng thái "xác nhận"
            ->get();

        // cập nhật số người vào bảng đặt bàn 
        foreach ($datBans as $datBan) {
            $datBan->so_nguoi = $soNguoi;
            $datBan->save();
        }

        return response()->json([
            'success' => true,
            'soNguoi' => $soNguoi,
        ]);
    }

    public function getHoaDonThanhToan(Request $request)
    {
        $maHoaDon = $request->input('maHoaDon');
        $hoaDon = HoaDon::where('ma_hoa_don', $maHoaDon)->first();

        if (!$hoaDon) {
            return response()->json(['error' => 'Không tìm thấy hóa đơn'], 404);
        }

        // lấy ra chi tiết hóa đơn
        $chiTietHoaDon = ChiTietHoaDon::where('hoa_don_id', $hoaDon->id)
            ->where('trang_thai', '!=', 'cho_xac_nhan')
            ->with(['monAn:id,ten,gia'])
            ->get()
            ->map(function ($chiTiet) {
                return [
                    'id' => $chiTiet->id,
                    'mon_an_id' => $chiTiet->monAn->id,
                    'tenMon' => $chiTiet->monAn->ten,
                    'don_gia' => $chiTiet->monAn->gia,
                    'trang_thai' => $chiTiet->trang_thai,
                    'so_luong' => $chiTiet->so_luong,
                    'thanh_tien' => $chiTiet->thanh_tien,
                ];
            });

        // danh sách id món chờ xác nhận
        $monAnChoXacNhan = ChiTietHoaDon::where('hoa_don_id', $hoaDon->id)
            ->where('trang_thai', 'cho_xac_nhan')
            ->with(['monAn:id,ten,gia'])
            ->get()
            ->map(function ($chiTiet) {
                return [
                    'id' => $chiTiet->id,
                ];
            });

        $tongTien = $chiTietHoaDon->sum('thanh_tien');
        $tongTienSauGiam = $tongTien;
        $today = Carbon::today();
        $maGiamGias = MaGiamGia::where('min_order_value', '<=', $tongTien)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->where('usage_limit', '>', 0)
            ->get()
            ->map(function ($maGiamGia) use ($hoaDon) {
                // Kiểm tra xem id_ma_giam của hóa đơn có phải null không
                if ($hoaDon->id_ma_giam === null) {
                    $maGiamGia->is_applied = false; // Không có mã giảm giá áp dụng
                } else {
                    // Kiểm tra mã giảm giá có trùng với mã giảm giá đã áp dụng trong hóa đơn
                    $isApplied = $hoaDon->id_ma_giam === $maGiamGia->id;
                    $maGiamGia->is_applied = $isApplied; // Gán giá trị cho thuộc tính is_applied
                }

                return $maGiamGia;
            });


        return response()->json([
            'data' => $maHoaDon,
            'chi_tiet_hoa_don' => $chiTietHoaDon,
            'tong_tien' => $tongTien,
            'mon_an_cho_xac_nhan' => $monAnChoXacNhan,
            'ma_giam_gia' => $maGiamGias,
            'tong_tien_sau_giam' => $tongTienSauGiam,
        ]);
    }

    public function saveNote(Request $request)
    {
        $idChiTiet = $request->input('id_chi_tiet');
        $ghiChu = $request->input('ghi_chu');

        // Cập nhật ghi chú vào hóa đơn
        ChiTietHoaDon::where('id', $idChiTiet)
            ->update(['ghi_chu' => $ghiChu]);

        return response()->json([
            'success' => true,
            'message' => 'Ghi chú đã được cập nhật thành công.',
            'chi_tiet' => $idChiTiet,
            'ghi_chu' => $ghiChu

        ]);
    }

    public function taoQr($ma)
    {
        // Tìm hóa đơn theo mã hóa đơn chứ không phải id
        $hoaDon = HoaDon::where('ma_hoa_don', $ma)->firstOrFail();

        $bankCode = 'ICB'; // ngân hàng
        $accountNumber = '104883178306';
        $amount = $hoaDon->tong_tien;
        $billCode = $hoaDon->ma_hoa_don;


        $desc = urlencode("Thanh toan hoa don $billCode - BQL");
        $qrUrl = "https://img.vietqr.io/image/{$bankCode}-{$accountNumber}-qr_only.png?amount={$amount}&addInfo={$desc}";

        return response()->json([
            'success' => true,
            'qr_url' => $qrUrl
        ]);
    }

    public function applyDiscount(Request $request)
    {
        $code = $request->input('code');
        $maHoaDon = $request->input('ma_hoa_don');

        // Lấy mã giảm giá và hóa đơn
        $maGiamGia = MaGiamGia::where('id', $code)->first();
        $hoaDon = HoaDon::where('ma_hoa_don', $maHoaDon)->first();

        if (!$maGiamGia || !$hoaDon) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy mã giảm giá hoặc hóa đơn.'
            ]);
        }

        // Nếu hóa đơn chưa có tổng tiền trước khi giảm, gán tổng tiền hiện tại vào
        if (is_null($hoaDon->tong_tien_truoc_khi_giam)) {
            $hoaDon->tong_tien_truoc_khi_giam = $hoaDon->tong_tien;
            $hoaDon->save(); // Cập nhật tổng tiền trước khi giảm
        }

        // Lấy tổng tiền trước khi giảm (sẽ không thay đổi khi đã có mã giảm giá)
        $tongTienTruocKhiGiam = $hoaDon->tong_tien_truoc_khi_giam;

        $tongTienSauGiam = $tongTienTruocKhiGiam; // Dựa trên tổng tiền trước khi giảm

        // Tính giảm giá
        if ($maGiamGia->type === 'percentage') {
            $tongTienSauGiam -= ($tongTienTruocKhiGiam * $maGiamGia->value / 100);
        } elseif ($maGiamGia->type === 'fixed') {
            $tongTienSauGiam -= $maGiamGia->value;
        }

        // Đảm bảo tổng tiền không âm
        $tongTienSauGiam = max($tongTienSauGiam, 0);

        // Cập nhật mã giảm và tổng tiền mới vào hóa đơn
        $hoaDon->update([
            'id_ma_giam' => $code, // Cập nhật mã giảm giá mới
            'tong_tien' => $tongTienSauGiam, // Cập nhật tổng tiền mới
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'code' => $code,
            'ma_hoa_don' => $maHoaDon,
            'tong_tien_truoc_khi_giam' => $tongTienTruocKhiGiam,
            'tong_tien_sau_giam' => $tongTienSauGiam,
        ]);
    }
}
