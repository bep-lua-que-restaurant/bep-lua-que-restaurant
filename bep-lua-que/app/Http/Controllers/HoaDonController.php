<?php

namespace App\Http\Controllers;

use App\Events\BanAnUpdated;
use App\Events\HoaDonAdded;
use App\Events\HoaDonUpdated;
use App\Models\HoaDon;
use App\Http\Requests\StoreHoaDonRequest;
use App\Http\Requests\UpdateHoaDonRequest;
use App\Models\BanAn;
use App\Models\ChiTietHoaDon;
use App\Models\DatBan;
use App\Models\HoaDonBan;
use App\Models\MonAn;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class HoaDonController extends Controller
{
    public function index(Request $request)
    {
        $query = HoaDon::with(['chiTietHoaDons.monAn'])
            ->leftJoin('hoa_don_bans', 'hoa_don_bans.hoa_don_id', '=', 'hoa_dons.id')
            ->leftJoin('ban_ans', 'ban_ans.id', '=', 'hoa_don_bans.ban_an_id')
            ->leftJoin('dat_bans', function ($join) {
                $join->on('dat_bans.ban_an_id', '=', 'ban_ans.id')
                    ->whereNotNull('dat_bans.khach_hang_id');
            })
            ->leftJoin('khach_hangs', 'khach_hangs.id', '=', 'dat_bans.khach_hang_id')
            ->select(
                'hoa_dons.id',
                'hoa_dons.ma_hoa_don',
                'hoa_dons.tong_tien',
                'hoa_dons.tong_tien_truoc_khi_giam',
                'hoa_dons.phuong_thuc_thanh_toan',
                'hoa_dons.created_at as ngay_tao',
                DB::raw('IFNULL(
                SUBSTRING_INDEX(
                    GROUP_CONCAT(
                        DISTINCT CASE 
                            WHEN khach_hangs.ho_ten IS NOT NULL 
                            THEN khach_hangs.ho_ten 
                            ELSE "Khách lẻ" 
                        END 
                        ORDER BY khach_hangs.ho_ten ASC
                        SEPARATOR ", "
                    ), 
                ",", 1), 
            "Không có khách") as ho_ten'),
                DB::raw('IFNULL(
                SUBSTRING_INDEX(
                    GROUP_CONCAT(
                        DISTINCT CASE 
                            WHEN khach_hangs.so_dien_thoai IS NOT NULL 
                            THEN khach_hangs.so_dien_thoai 
                            ELSE "Không có số" 
                        END 
                        ORDER BY khach_hangs.so_dien_thoai ASC
                        SEPARATOR ", "
                    ), 
                ",", 1), 
            "Không có số") as so_dien_thoai'),
                DB::raw('IFNULL(GROUP_CONCAT(DISTINCT ban_ans.ten_ban ORDER BY ban_ans.ten_ban ASC SEPARATOR ", "), "Chưa có bàn") as ten_ban')
            )
            ->groupBy('hoa_dons.id', 'hoa_dons.ma_hoa_don', 'hoa_dons.tong_tien', 'hoa_dons.tong_tien_truoc_khi_giam', 'hoa_dons.phuong_thuc_thanh_toan', 'hoa_dons.created_at')
            ->orderByDesc('hoa_dons.created_at');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('hoa_dons.ma_hoa_don', 'like', "%{$search}%")
                    ->orWhereRaw("IFNULL(khach_hangs.ho_ten, '') LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("IFNULL(khach_hangs.so_dien_thoai, '') LIKE ?", ["%{$search}%"]);
            });
        }

        $hoa_don = $query->paginate(10);
        // dd($hoa_don);`
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.hoadon.listhoadon', compact('hoa_don'))->render(),
                'pagination' => $hoa_don->links('pagination::bootstrap-5')->toHtml()
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

        $tenMon = MonAn::where('id', $monAnId)->select('ten')->first();
        if (!$tenMon) {
            return response()->json(['error' => 'Món ăn không tồn tại!'], 404);
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
                'tong_tien_truoc_khi_giam' => 0.00,
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
            ->where('trang_thai', 'cho_xac_nhan')
            ->first();

        if ($chiTietHoaDon) {
            // Nếu món ăn đã có, tăng số lượng
            $chiTietHoaDon->increment('so_luong');
            $chiTietHoaDon->increment('thanh_tien', $giaMon);
        } else {
            // Nếu không có 'cho_xac_nhan', tạo bản ghi mới với trạng thái 'cho_xac_nhan'
            ChiTietHoaDon::create([
                'hoa_don_id' => $hoaDon->id,
                'mon_an_id' => $monAnId,
                'so_luong' => 1,
                'don_gia' => $giaMon,
                'thanh_tien' => $giaMon,
                'trang_thai' => 'cho_xac_nhan'
            ]);
            $hoaDon = HoaDon::with('chiTietHoaDons')->find($hoaDon->id);
            broadcast(new HoaDonAdded($hoaDon))->toOthers();
        }
        // Cập nhật tổng tiền trong bảng `hoa_don`
        $tongTien = ChiTietHoaDon::where('hoa_don_id', $hoaDon->id)->sum('thanh_tien');
        $hoaDon->update([
            'tong_tien' => $tongTien,
            'tong_tien_truoc_khi_giam' => $tongTien // Cập nhật cả tong_tien_truoc_khi_giam
        ]);

        // 🔥 Nếu hóa đơn có món ăn, đổi trạng thái bàn thành "co_khach"
        $soLuongMon = ChiTietHoaDon::where('hoa_don_id', $hoaDon->id)->count();
        if ($soLuongMon > 0) {
            BanAn::where('id', $banAnId)->update(['trang_thai' => 'co_khach']);
            $ban_an = BanAn::find($banAnId);
            broadcast(new BanAnUpdated($ban_an))->toOthers();
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

        broadcast(new HoaDonUpdated($hoaDon))->toOthers();
        // event(new HoaDonUpdated($hoaDon));
        return response()->json([
            'data' => $hoaDon,
            'ten_mon' => $tenMon->ten,
        ], 200);
    }


    public function show($id)
    {
        $hoaDon = HoaDon::with([
            'chiTietHoaDons.monAn' => function ($query) {
                $query->withTrashed(); // Lấy luôn món ăn đã bị xóa
            },
            'banAns' => function ($query) {
                $query->withTrashed(); // Lấy luôn bàn ăn đã bị xóa
            },
            'billImages' // Thêm quan hệ billImages để lấy ảnh
        ])
            ->leftJoin('hoa_don_bans', 'hoa_don_bans.hoa_don_id', '=', 'hoa_dons.id')
            ->leftJoin('ban_ans', 'ban_ans.id', '=', 'hoa_don_bans.ban_an_id')
            ->leftJoin('dat_bans', function ($join) {
                $join->on('dat_bans.ban_an_id', '=', 'ban_ans.id')
                    ->whereNotNull('dat_bans.khach_hang_id');
            })
            ->leftJoin('khach_hangs', 'khach_hangs.id', '=', 'dat_bans.khach_hang_id')
            ->leftJoin('ma_giam_gias', 'hoa_dons.id_ma_giam', '=', 'ma_giam_gias.id')
            ->select(
                'hoa_dons.*',
                'khach_hangs.ho_ten as ten_khach_hang',
                'khach_hangs.so_dien_thoai',
                'ma_giam_gias.id as id_ma_giam',
                'ma_giam_gias.code as code'
            )
            ->where('hoa_dons.id', $id)
            ->withTrashed() // Không lấy bản ghi của `hoa_dons` dù đã bị xóa mềm
            ->firstOrFail();

        return view('admin.hoadon.show', compact('hoaDon'));
    }
}
