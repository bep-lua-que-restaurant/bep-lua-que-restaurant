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
use Barryvdh\DomPDF\Facade\Pdf;

class HoaDonController extends Controller
{
    public function index(Request $request)
    {
        $query = HoaDon::with(['chiTietHoaDons.monAn']) // Load chi tiết hóa đơn + món ăn
        ->leftJoin('hoa_don_bans', 'hoa_don_bans.hoa_don_id', '=', 'hoa_dons.id')
        ->leftJoin('ban_ans', 'ban_ans.id', '=', 'hoa_don_bans.ban_an_id')
        ->leftJoin('dat_bans', function ($join) {
            $join->on('dat_bans.ban_an_id', '=', 'ban_ans.id')
                 ->whereNotNull('dat_bans.khach_hang_id');
        })
        ->leftJoin('khach_hangs', 'khach_hangs.id', '=', 'dat_bans.khach_hang_id')
        ->select(
            'hoa_dons.*',  // Thêm dòng này để giữ toàn bộ dữ liệu của hóa đơn
            'khach_hangs.ho_ten',
            'khach_hangs.so_dien_thoai',
            'ban_ans.ten_ban',
            'hoa_dons.created_at as ngay_tao'
        )
        ->orderByDesc('hoa_dons.created_at');
    
    
        // Kiểm tra nếu có từ khóa tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('hoa_dons.ma_hoa_don', 'like', '%' . $request->search . '%')
                  ->orWhere('khach_hangs.ho_ten', 'like', '%' . $request->search . '%')
                  ->orWhere('khach_hangs.so_dien_thoai', 'like', '%' . $request->search . '%'); // Tìm theo số điện thoại
            });
        }
        
    
        // ❌ KHÔNG DÙNG get() TRƯỚC paginate()
        $hoa_don = $query->paginate(10); // ✅ Dùng paginate() trực tiếp trên query
    
        // Nếu là Ajax request, trả về HTML của bảng luôn
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.hoadon.index_table', compact('hoa_don'))->render(),
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
        $hoaDon = HoaDon::with(['chiTietHoaDons.monAn', 'banAns'])
        ->leftJoin('hoa_don_bans', 'hoa_don_bans.hoa_don_id', '=', 'hoa_dons.id')
        ->leftJoin('ban_ans', 'ban_ans.id', '=', 'hoa_don_bans.ban_an_id')
        ->leftJoin('dat_bans', function ($join) {
            $join->on('dat_bans.ban_an_id', '=', 'ban_ans.id')
                 ->whereNotNull('dat_bans.khach_hang_id'); 
        })
        ->leftJoin('khach_hangs', 'khach_hangs.id', '=', 'dat_bans.khach_hang_id')
        ->select(
            'hoa_dons.*',
            'khach_hangs.ho_ten as ten_khach_hang',
            'khach_hangs.so_dien_thoai'
        )
        ->where('hoa_dons.id', $id)
        ->firstOrFail();

        return view('admin.hoadon.show', compact('hoaDon'));
    }
    //in hóa đơn
//     public function printInvoice($id)
// {
//     $hoaDon = HoaDon::with(['banAns']) // Chỉ lấy thông tin hóa đơn, tránh dữ liệu lặp
//     ->leftJoin('hoa_don_bans', 'hoa_don_bans.hoa_don_id', '=', 'hoa_dons.id')
//     ->leftJoin('ban_ans', 'ban_ans.id', '=', 'hoa_don_bans.ban_an_id')
//     ->leftJoin('dat_bans', function ($join) {
//         $join->on('dat_bans.ban_an_id', '=', 'ban_ans.id')
//              ->whereNotNull('dat_bans.khach_hang_id'); 
//     })
//     ->leftJoin('khach_hangs', 'khach_hangs.id', '=', 'dat_bans.khach_hang_id')
//     ->select(
//         'hoa_dons.*',
//         'khach_hangs.ho_ten as ten_khach_hang',
//         'khach_hangs.so_dien_thoai'
//     )
//     ->where('hoa_dons.id', $id)
//     ->firstOrFail(); // Lấy một bản ghi hóa đơn duy nhất
//     $chiTietHoaDon = ChiTietHoaDon::where('hoa_don_id', $id)
//     ->join('mon_ans', 'mon_ans.id', '=', 'chi_tiet_hoa_dons.mon_an_id')
//     ->select(
//         'mon_ans.ten as ten_mon',
//         'chi_tiet_hoa_dons.so_luong',
//         'chi_tiet_hoa_dons.don_gia',
//         'mon_ans.gia' // Lấy thêm giá bán từ bảng mon_ans
//     )
//     ->get(); // Lấy danh sách món ăn



//     $pdf = Pdf::loadView('admin.hoadon.pdf', compact('hoaDon', 'chiTietHoaDon'));

//     return $pdf->stream('hoa_don_' . $hoaDon->id . '.pdf');
    
// }

}
