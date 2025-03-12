<?php

namespace App\Http\Controllers;

use App\Events\BanAnUpdated;
use App\Events\DatBanCreated;
use App\Http\Requests\StoreDatBanRequest;
use App\Models\DatBan;
use App\Http\Requests\UpdateDatBanRequest;
use App\Models\BanAn;
// use Flasher\Laravel\Http\Request;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\DatBanMail;
use App\Models\HoaDon;
use App\Models\HoaDonBan;

use Illuminate\Support\Str;

class DatBanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = \Carbon\Carbon::today();
        return view('admin.datban.index', compact('today'));
    }

    public function indexNgay()
    {
        // $today = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        $today = \Carbon\Carbon::today();
        return view('admin.datban.indexngay', compact('today'));
    }

    public function getDatBanByDate(Request $request)
    {
        $date = $request->input('date', Carbon::now('Asia/Ho_Chi_Minh')->toDateString());

        // Lấy danh sách bàn (PHÂN TRANG 10 bàn/trang)
        $banPhong = BanAn::whereNull('deleted_at')
            ->whereHas('phongAn', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderBy('vi_tri')
            ->orderBy('id')
            ->paginate(10); // Sử dụng phân trang

        // Lấy danh sách đặt bàn theo ngày
        $datBans = DatBan::whereDate('thoi_gian_den', $date)
            ->whereIn('ban_an_id', $banPhong->pluck('id'))
            ->whereNull('deleted_at')
            ->get();

        return response()->json([
            'banPhong' => $banPhong, // Trả về danh sách có phân trang
            'datBans' => $datBans
        ]);
    }

    public function DanhSach()
    {
        $banhSachDatban = DatBan::select(
            'dat_bans.ma_dat_ban',
            'dat_bans.thoi_gian_den',
            'khach_hangs.id as khach_hang_id',
            'khach_hangs.ho_ten',
            'khach_hangs.so_dien_thoai',
            'dat_bans.so_nguoi',
            DB::raw("GROUP_CONCAT(DISTINCT ban_ans.ten_ban ORDER BY ban_ans.ten_ban SEPARATOR ', ') as danh_sach_ban"),
            'dat_bans.trang_thai',
            'dat_bans.mo_ta'
        )
            ->join('khach_hangs', 'dat_bans.khach_hang_id', '=', 'khach_hangs.id')
            ->join('ban_ans', 'dat_bans.ban_an_id', '=', 'ban_ans.id')
            ->groupBy('dat_bans.ma_dat_ban', 'dat_bans.thoi_gian_den', 'khach_hangs.id', 'khach_hangs.ho_ten', 'khach_hangs.so_dien_thoai', 'dat_bans.so_nguoi', 'dat_bans.trang_thai', 'dat_bans.mo_ta')
            ->orderBy('dat_bans.thoi_gian_den', 'desc')
            ->paginate(10);

        return view('admin.datban.danhsach', compact('banhSachDatban'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function filterDatBan(Request $request)
    {
        $search = $request->input('search');
        $trang_thai = $request->input('trang_thai');

        $query = DatBan::select(
            'dat_bans.id',
            'dat_bans.thoi_gian_den',
            'khach_hangs.ho_ten',
            'khach_hangs.so_dien_thoai',
            'dat_bans.so_nguoi',
            DB::raw("GROUP_CONCAT(ban_ans.ten_ban ORDER BY ban_ans.ten_ban SEPARATOR ', ') as danh_sach_ban"),
            'dat_bans.trang_thai',
            'dat_bans.mo_ta'
        )
            ->join('khach_hangs', 'dat_bans.khach_hang_id', '=', 'khach_hangs.id')
            ->join('ban_ans', 'dat_bans.ban_an_id', '=', 'ban_ans.id')
            ->groupBy('dat_bans.id', 'dat_bans.thoi_gian_den', 'khach_hangs.ho_ten', 'khach_hangs.so_dien_thoai', 'dat_bans.so_nguoi', 'dat_bans.trang_thai', 'dat_bans.mo_ta')
            ->orderBy('dat_bans.id', 'desc'); // Sắp xếp ID mới nhất

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('khach_hangs.ho_ten', 'LIKE', "%{$search}%")
                    ->orWhere('khach_hangs.so_dien_thoai', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($trang_thai)) {
            $query->where('dat_bans.trang_thai', $trang_thai);
        }

        // Áp dụng phân trang
        $datBans = $query->paginate(10);

        return response()->json([
            'data' => $datBans->items(),   // Danh sách đặt bàn
            'pagination' => (string) $datBans->links('pagination::bootstrap-5') // HTML phân trang
        ]);
    }

    public function create(Request $request)
    {
        // Lấy các tham số từ query parameters (như ban đầu)
        $tenBan = $request->query('ten_ban', 'Không xác định');
        $idBan = $request->query('id_ban', '');
        $time = $request->query('time', '08:00');
        $date = $request->query('date', now()->format('Y-m-d'));
        $thoiGianDen = $request->query('thoi_gian_den', "$date $time");

        // dd($idBan, $time, $date, $thoiGianDen);


        // Dữ liệu ban ăn ban đầu (có thể có sẵn hoặc từ AJAX nếu người dùng chọn thời gian)
        $banAns = BanAn::join('phong_ans', 'ban_ans.vi_tri', '=', 'phong_ans.id')
            ->whereNull('ban_ans.deleted_at')
            ->whereNull('phong_ans.deleted_at')
            ->orderBy('ban_ans.vi_tri')
            ->orderBy('ban_ans.id')
            ->select('ban_ans.id', 'ban_ans.ten_ban', 'ban_ans.so_ghe', 'phong_ans.ten_phong_an')
            ->get();

        return view('admin.datban.create', compact('tenBan', 'idBan', 'time', 'date', 'banAns', 'thoiGianDen'));
    }
    public function filterBanAnByTime(Request $request)
    {
        $thoiGianDen = $request->input('thoi_gian_den');
        $gioDuKienGio = $request->input('gio_du_kien_gio', 1); // Mặc định là 1 giờ
        $gioDuKienPhut = $request->input('gio_du_kien_phut', 0); // Mặc định là 00 phút

        if (!$thoiGianDen) {
            return response()->json(['error' => 'Thời gian đến không hợp lệ'], 400);
        }

        $thoiGianDenCarbon = Carbon::parse($thoiGianDen, 'Asia/Ho_Chi_Minh'); // Set múi giờ VN
        $endTime = $thoiGianDenCarbon->copy()->addHours($gioDuKienGio)->addMinutes($gioDuKienPhut);

        // Lấy danh sách bàn ăn và kiểm tra xem bàn có bị đặt trong khoảng thời gian đã chọn không
        $banAns = BanAn::join('phong_ans', 'ban_ans.vi_tri', '=', 'phong_ans.id')
            ->leftJoin('dat_bans', function ($join) use ($thoiGianDenCarbon) {
                $join->on('ban_ans.id', '=', 'dat_bans.ban_an_id')
                    ->whereRaw('
                    ? BETWEEN dat_bans.thoi_gian_den 
                    AND ADDTIME(
                        dat_bans.thoi_gian_den, 
                        SEC_TO_TIME(
                            CEIL(TIME_TO_SEC(dat_bans.gio_du_kien) / 1800) * 1800
                        )
                    )
                ', [$thoiGianDenCarbon]);
            })
            ->whereNull('ban_ans.deleted_at')
            ->whereNull('phong_ans.deleted_at')
            ->orderBy('ban_ans.vi_tri')
            ->orderBy('ban_ans.id')
            ->select(
                'ban_ans.id',
                'ban_ans.ten_ban',
                'ban_ans.so_ghe',
                'phong_ans.ten_phong_an',
                'dat_bans.trang_thai',
                'dat_bans.thoi_gian_den',
                DB::raw("IF(dat_bans.trang_thai IN ('dang_xu_ly', 'xac_nhan'), 1, 0) as da_duoc_dat")
            )
            ->get();




        return response()->json($banAns);
    }



    // Hàm để xử lý Ajax tìm kiếm khách hàng
    public function searchCustomer(Request $request)
    {
        $searchTerm = $request->get('search', '');
        $customers = [];

        if ($searchTerm) {
            $customers = KhachHang::where('ho_ten', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('so_dien_thoai', 'LIKE', '%' . $searchTerm . '%')
                ->get();
        }

        return response()->json([
            'customers' => $customers
        ]);
    }

    public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'required|string|max:20',
            'num_people'     => 'required|integer|min:1',
            'selectedIds'    => 'required|array',
            'thoi_gian_den'  => 'required|date_format:Y-m-d H:i:s',
            'gio_du_kien'    => 'required|date_format:H:i:s',

        ]);

        // Kiểm tra xem khách hàng đã tồn tại chưa
        $customer = KhachHang::where('so_dien_thoai', $request->customer_phone)->first();

        // Nếu không có khách hàng, tạo mới
        if (!$customer) {
            $customer = KhachHang::create([
                'ho_ten'        => $request->customer_name,
                'so_dien_thoai' => $request->customer_phone,
                'email'         => $request->customer_email,
            ]);
        }

        // Khởi tạo danh sách đặt bàn
        $danhSachBanDat = [];

        // Tạo mã đặt bàn duy nhất
        $maDatBan = DatBan::generateMaDatBan(); // Hoặc gọi hàm generateMaDatBan()

        // Lưu đơn đặt bàn cho từng bàn ăn được chọn
        foreach ($request->selectedIds as $banAnId) {
            $datBan = DatBan::create([
                'khach_hang_id' => $customer->id,
                'so_dien_thoai' => $customer->so_dien_thoai,
                'thoi_gian_den' => $request->thoi_gian_den,
                'gio_du_kien'   => $request->gio_du_kien, // Giờ dự kiến sử dụng bàn
                'mo_ta'         => $request->mo_ta ?? 'Đặt bàn qua hệ thống',
                'so_nguoi'      => $request->num_people,
                'ban_an_id'     => $banAnId,
                'ma_dat_ban'    => $maDatBan, // Dùng chung mã đặt bàn
                'trang_thai'    => 'dang_xu_ly', // Trạng thái mặc định

            ]);

            $danhSachBanDat[] = $datBan;
        }

        // Phát sự kiện sau khi đặt bàn (nếu cần xử lý tiếp)
        event(new DatBanCreated($datBan));

        // Gửi email xác nhận đặt bàn (chỉ gửi 1 email cho khách)
        if (!empty($customer->email)) {
            Mail::to($customer->email)->send(new DatBanMail($customer, $danhSachBanDat));
        }

        // Redirect về danh sách đặt bàn với thông báo thành công
        return redirect()->route('dat-ban.index')->with('success', 'Đặt bàn thành công!');
    }

    // public function store(StoreDatBanRequest $request)
    // {
    //     try {

    //         dd($request->all());

    //         // Kiểm tra khách hàng đã tồn tại
    //         $customer = KhachHang::where('so_dien_thoai', $request->customer_phone)->first();

    //         // Nếu không có khách hàng, tạo mới
    //         if (!$customer) {
    //             $customer = KhachHang::create([
    //                 'ho_ten' => $request->customer_name,
    //                 'so_dien_thoai' => $request->customer_phone,
    //                 'email' => $request->customer_email,
    //             ]);
    //         }

    //         $danhSachBanDat = []; // Lưu danh sách các bàn được đặt

    //         // Tạo mã đặt bàn trước, dùng chung cho tất cả các bàn
    //         $maDatBan = DatBan::generateMaDatBan();

    //         // Lưu đơn đặt bàn cho từng bàn ăn được chọn
    //         foreach ($request->selectedIds as $banAnId) {
    //             $datBan = DatBan::create([
    //                 'khach_hang_id' => $customer->id,
    //                 'so_dien_thoai' => $customer->so_dien_thoai,
    //                 'thoi_gian_den' => $request->thoi_gian_den,
    //                 'gio_du_kien' => $request->gio_du_kien, // ⚡️ Giờ dự kiến sử dụng bàn
    //                 'mo_ta' => $request->mo_ta,
    //                 'so_nguoi' => $request->num_people,
    //                 'ban_an_id' => $banAnId,
    //                 'ma_dat_ban' => $maDatBan, // ⚡️ Dùng chung một mã đặt bàn
    //             ]);

    //             $danhSachBanDat[] = $datBan;
    //         }

    //         // Phát sự kiện sau khi đặt bàn (nếu cần xử lý tiếp)
    //         event(new DatBanCreated($datBan));

    //         // Gửi email xác nhận đặt bàn (chỉ gửi 1 email cho khách)
    //         if (!empty($customer->email)) {
    //             Mail::to($customer->email)->send(new DatBanMail($customer, $danhSachBanDat));
    //         }

    //         // Redirect về danh sách đặt bàn với thông báo thành công
    //         return redirect()->route('dat-ban.index')->with('success', 'Đặt bàn thành công!');
    //     } catch (\Exception $e) {
    //         \Log::error('Lỗi xảy ra: ' . $e->getMessage()); // Ghi log lỗi
    //         return response()->json(['error' => 'Đã xảy ra lỗi!'], 500);
    //     }
    // }

    public function show($maDatBan)
    {
        // Lấy thông tin đặt bàn dựa trên mã đặt bàn
        $datBans = DatBan::where('ma_dat_ban', $maDatBan)
            ->with(['khachHang', 'banAn', 'banAn.phongAn']) // Load thêm thông tin khách hàng, bàn ăn và phòng ăn
            ->get();

        // Kiểm tra nếu không tìm thấy đặt bàn
        if ($datBans->isEmpty()) {
            return redirect()->route('dat-ban.index')->with('error', 'Không tìm thấy đặt bàn!');
        }

        // Lấy thông tin đặt bàn đầu tiên trong danh sách (do các bản ghi cùng mã đặt bàn có chung thông tin)
        $datBan = $datBans->first();

        // Trả về view với danh sách đặt bàn (để hiển thị tất cả bàn đã đặt trong cùng một đơn)
        return view('admin.datban.show', compact('datBan', 'datBans'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    // use Carbon\Carbon;

    // use Carbon\Carbon;

    public function edit($maDatBan)
    {
        // Lấy thông tin đặt bàn chính
        $datBan = DatBan::with('khachHang')->where('ma_dat_ban', $maDatBan)->first();

        if (!$datBan) {
            return redirect()->route('dat-ban.index')->with('error', 'Không tìm thấy đặt bàn!');
        }

        // Lấy tất cả bàn ăn để hiển thị
        $banAns = BanAn::whereNull('deleted_at')->paginate(10);


        // Lấy các bàn của đơn đặt hiện tại (bàn đang được chỉnh sửa)
        $datBanCurrent = DatBan::where('ma_dat_ban', $maDatBan)
            ->get();
        // dd($datBanCurrent->toArray());

        // Lấy tất cả các đơn đặt bàn, trừ ma_dat_ban hiện tại
        $datBansOther = DatBan::where('ma_dat_ban', '!=', $maDatBan)->get();
        // dd($datBansOther->toArray());

        // Truyền dữ liệu vào view
        return view('admin.datban.edit', compact('datBan', 'banAns', 'datBanCurrent', 'datBansOther', 'maDatBan'));
    }


    /**
     * Update the specified resource in storage.
     */

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
    public function update(UpdateDatBanRequest $request, $maDatBan)
    {
        // dd($request->toArray());
        // dd($maDatBan);
        // 1. Xoá hết đơn đặt bàn với ma_dat_ban hiện tại
        DatBan::where('ma_dat_ban', $maDatBan)->delete();


        $thoiGianDen = Carbon::createFromFormat('Y-m-d', $request->ngay_den)->format('Y-m-d') . ' ' . $request->thoi_gian_den . ':00';
        $gioDuKien = Carbon::createFromFormat('H:i', $request->gio_du_kien)->format('H:i') . ':00';
        // 2. Thêm mới đơn đặt bàn cho từng ban_an_id
        $banAnIds = json_decode($request->ban_an_ids, true);

        foreach ($banAnIds as $banAnId) {
            $datBan = DatBan::create([
                'ma_dat_ban'     => $maDatBan,
                'ban_an_id'      => $banAnId,
                'thoi_gian_den' => $thoiGianDen,
                'gio_du_kien' => $gioDuKien,
                'khach_hang_id'  => $request->khach_hang_id,
                'so_dien_thoai'  => $request->so_dien_thoai,
                'so_nguoi'       => $request->so_nguoi,
                'mo_ta'          => $request->mo_ta,
                'trang_thai'     => 'xac_nhan',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        // 3. Tạo hóa đơn mới
        $maHoaDon = $this->generateMaHoaDon();
        $hoaDon = HoaDon::create([
            'ma_hoa_don' => $maHoaDon,
            'khach_hang_id' => $request->khach_hang_id,
            'tong_tien' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);



        // 4. Tạo danh sách hoá đơn bàn
        foreach ($banAnIds as $banAnId) {
            HoaDonBan::create([
                'ban_an_id' => $banAnId,
                'hoa_don_id' => $hoaDon->id,
                'trang_thai' => 'dang_xu_ly',
            ]);

            // ✅ Cập nhật trạng thái bàn ăn thành "có khách"
            $banAn = BanAn::find($banAnId);
            if ($banAn) {
                $banAn->update(['trang_thai' => 'co_khach']);

                // ✅ Phát sự kiện real-time khi trạng thái bàn thay đổi
                event(new BanAnUpdated($banAn));
            }
        }

        // ✅ Phát sự kiện khi đơn đặt bàn được cập nhật thành công
        event(new DatBanCreated($datBan));

        return redirect()->back()->with('success', 'Cập nhật thành công! Hóa đơn đã được tạo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($maDatBan)
    {
        $datBan = DatBan::where('ma_dat_ban', $maDatBan)->first();

        if (!$datBan) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn đặt bàn!');
        }

        if ($datBan->trang_thai === 'dang_xu_ly') {
            // Cập nhật trạng thái
            DatBan::where('thoi_gian_den', $datBan->thoi_gian_den)
                ->where('so_dien_thoai', $datBan->so_dien_thoai)
                ->where('created_at', $datBan->created_at)
                ->update(['trang_thai' => 'da_huy']);

            event(new DatBanCreated($datBan));

            return redirect()->back()->with('success', 'Tất cả đơn đặt bàn đã được hủy thành công!');
        } else {
            return redirect()->back()->with('error', 'Không thể hủy, trạng thái không phải "Đang xử lý".');
        }
    }
}
