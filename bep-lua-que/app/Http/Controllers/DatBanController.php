<?php

namespace App\Http\Controllers;

use App\Events\BanAnUpdated;
use App\Events\DatBanCreated;
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

class DatBanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy danh sách bàn
        $banPhong = BanAn::whereNull('deleted_at')
            ->whereHas('phongAn', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderBy('vi_tri')
            ->orderBy('id')
            ->get(); // Dùng get() để lấy tất cả các bàn

        // Lấy ngày hôm nay
        // $today = Carbon::today();
        $today = Carbon::now('Asia/Ho_Chi_Minh'); // Đảm bảo đúng múi giờ


        // Lấy các đơn đặt bàn trong ngày hôm nay
        $datBansToday = DatBan::whereDate('thoi_gian_den', $today)
            ->whereIn('ban_an_id', $banPhong->pluck('id'))
            ->whereNull('deleted_at')
            ->get();
        // dd($datBansToday->toArray());

        // Lấy các đơn đặt bàn trong tuần này
        $startDate = Carbon::today(); // Hôm nay
        $endDate = $startDate->copy()->addDays(7)->endOfDay(); // 7 ngày tiếp theo

        $datBansWeek = DatBan::whereBetween('thoi_gian_den', [$startDate, $endDate])
            ->whereIn('ban_an_id', $banPhong->pluck('id'))
            ->whereNull('deleted_at')
            ->get();

        // dd($datBansWeek->toArray());

        // Lấy các đơn đặt bàn trong tháng này
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth()->endOfDay();
        $datBansMonth = DatBan::whereBetween('thoi_gian_den', [$startOfMonth, $endOfMonth])
            ->whereIn('ban_an_id', $banPhong->pluck('id'))
            ->whereNull('deleted_at')
            ->get();
        // dd($datBansMonth->toArray());
        return view('admin.datban.index', compact('banPhong', 'datBansToday', 'datBansWeek', 'datBansMonth', 'today'));
    }

    public function DanhSach()
    {
        $banhSachDatban = DatBan::select(
            DB::raw("MIN(dat_bans.id) as datban_id"), // Lấy ID nhỏ nhất trong nhóm
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
            ->groupBy(
                'dat_bans.thoi_gian_den',
                'khach_hangs.id',
                'khach_hangs.ho_ten',
                'khach_hangs.so_dien_thoai',
                'dat_bans.so_nguoi',
                'dat_bans.trang_thai',
                'dat_bans.mo_ta'
            )
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

        if (!$thoiGianDen) {
            return response()->json(['error' => 'Thời gian đến không hợp lệ'], 400);
        }

        $thoiGianDenCarbon = Carbon::parse($thoiGianDen, 'Asia/Ho_Chi_Minh'); // Set múi giờ VN
        $startTime = $thoiGianDenCarbon->copy()->subHour();
        $endTime = $thoiGianDenCarbon->copy()->addHour();

        // Lấy danh sách bàn ăn và kiểm tra xem bàn có bị đặt không
        $banAns = BanAn::join('phong_ans', 'ban_ans.vi_tri', '=', 'phong_ans.id')
            ->leftJoin('dat_bans', function ($join) use ($startTime, $endTime) {
                $join->on('ban_ans.id', '=', 'dat_bans.ban_an_id')
                    ->whereBetween('dat_bans.thoi_gian_den', [$startTime, $endTime]);
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


        // Trả về kết quả dưới dạng JSON để JavaScript xử lý
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
        // dd($request->selectedIds);
        $request->validate([
            // Kiểm tra họ và tên khách hàng
            'customer_name' => 'required|string|max:255',  // Yêu cầu nhập, là chuỗi, tối đa 255 ký tự
            'customer_phone' => 'required|string|max:15',  // Yêu cầu nhập, là chuỗi, tối đa 15 ký tự
            'thoi_gian_den' => 'required|date|after_or_equal:today',  // Yêu cầu nhập, phải là ngày hợp lệ và phải sau hoặc bằng ngày hiện tại
            'num_people' => 'required|integer|min:1',  // Yêu cầu nhập, là số nguyên và tối thiểu là 1
            'selectedIds' => 'required|array|min:1',  // Yêu cầu chọn ít nhất một bàn ăn
        ], [
            // Thông báo lỗi tùy chỉnh cho từng trường

            'customer_name.required' => 'Họ và tên là bắt buộc.',
            'customer_name.string' => 'Họ và tên phải là chuỗi ký tự.',
            'customer_name.max' => 'Họ và tên không được vượt quá 255 ký tự.',

            'customer_phone.required' => 'Số điện thoại là bắt buộc.',
            'customer_phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'customer_phone.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
            'thoi_gian_den.required' => 'Thời gian đến là bắt buộc.',
            'thoi_gian_den.date' => 'Thời gian đến phải là một ngày hợp lệ.',
            'thoi_gian_den.after_or_equal' => 'Thời gian đến không được sớm hơn ngày hiện tại.',

            'num_people.required' => 'Số người là bắt buộc.',
            'num_people.integer' => 'Số người phải là một số nguyên.',
            'num_people.min' => 'Số người phải lớn hơn hoặc bằng 1.',

            'selectedIds.required' => 'Bàn ăn là bắt buộc.',
            'selectedIds.array' => 'Bàn ăn phải là một mảng.',
            'selectedIds.min' => 'Cần chọn ít nhất một bàn ăn.',
        ]);

        // Kiểm tra khách hàng đã tồn tại
        $customer = KhachHang::where('so_dien_thoai', $request->customer_phone)
            ->first();

        // Nếu không có khách hàng, tạo mới
        if (!$customer) {
            $customer = KhachHang::create([
                'ho_ten' => $request->customer_name,
                'so_dien_thoai' => $request->customer_phone,
                'email' => $request->customer_email,

            ]);
        }

        $danhSachBanDat = []; // Lưu danh sách các bàn được đặt

        // Lưu đơn đặt bàn
        foreach ($request->selectedIds as $banAnId) {
            $datBan = DatBan::create([
                'khach_hang_id' => $customer->id,
                'so_dien_thoai' => $customer->so_dien_thoai,
                'thoi_gian_den' => $request->thoi_gian_den,
                'mo_ta' => $request->mo_ta,
                'so_nguoi' => $request->num_people,
                'ban_an_id' => $banAnId,
            ]);

            $danhSachBanDat[] = $datBan;
        }
        // Phát sự kiện ngay sau khi tạo bản ghi
        event(new DatBanCreated(datBan: $datBan));

        // Sau khi đặt tất cả bàn, gửi một email duy nhất
        Mail::to($customer->email)->send(new DatBanMail($customer, $danhSachBanDat));

        return redirect()->route('dat-ban.index')->with('success', 'Đặt bàn thành công!');
    }

    public function show($id)
    {
        // Lấy thông tin đặt bàn của khách hàng theo ID
        $datBan = DatBan::with(['khachHang', 'banAn', 'banAn.phongAn'])
            ->find($id);

        // Kiểm tra nếu không tìm thấy đặt bàn
        if (!$datBan) {
            return redirect()->route('dat-ban.index')->with('error', 'Không tìm thấy đặt bàn!');
        }

        // Lấy tất cả các bàn đã đặt của khách hàng trong cùng thời gian đến
        $datBans = DatBan::where('khach_hang_id', $datBan->khach_hang_id)
            ->where('so_dien_thoai', $datBan->so_dien_thoai)
            ->where('thoi_gian_den', $datBan->thoi_gian_den)
            ->where('so_nguoi', $datBan->so_nguoi)
            ->where('trang_thai', $datBan->trang_thai)
            ->where('mo_ta', $datBan->mo_ta)
            ->with(['banAn', 'banAn.phongAn']) // Load các bàn và phòng
            ->get();

        // Lấy các đơn đặt bàn trong khoảng thời gian từ created_at đến 5 phút sau
        $datBansCreated = DatBan::whereBetween('created_at', [
            $datBan->created_at,
            $datBan->created_at->copy()->addMinutes(1) // Dùng copy() để tránh thay đổi giá trị gốc
        ])
            ->where('thoi_gian_den', $datBan->thoi_gian_den) // Kiểm tra trùng thời gian đến
            ->with(['banAn', 'banAn.phongAn']) // Load thêm thông tin bàn ăn và phòng ăn
            ->get();


        // Trả về view với thông tin đặt bàn và bàn đã chọn
        return view('admin.datban.show', compact('datBan', 'datBans', 'datBansCreated'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Lấy thông tin đặt bàn chính
        $datBan = DatBan::with('khachHang')->find($id);

        if (!$datBan) {
            return redirect()->route('dat-ban.index')->with('error', 'Không tìm thấy đặt bàn!');
        }

        // Lấy tất cả các bàn mà khách hàng này đã đặt trong cùng thời gian đến
        $datBans = DatBan::where('khach_hang_id', $datBan->khach_hang_id)
            ->where('thoi_gian_den', $datBan->thoi_gian_den)
            ->pluck('ban_an_id')
            ->toArray();

        // Lấy tất cả bàn ăn để hiển thị
        $banAns = BanAn::all();

        return view('admin.datban.edit', compact('datBan', 'banAns', 'datBans'));
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
    public function update(UpdateDatBanRequest $request, DatBan $datBan)
    {
        if ($datBan->trang_thai === 'dang_xu_ly') {
            // Cập nhật trạng thái đặt bàn
            DatBan::where('thoi_gian_den', $datBan->thoi_gian_den)
                ->where('so_dien_thoai', $datBan->so_dien_thoai)
                ->where('created_at', $datBan->created_at)
                ->update(['trang_thai' => 'xac_nhan']);

            // Tạo hóa đơn mới
            $maHoaDon = $this->generateMaHoaDon(); // Gọi function tạo mã hóa đơn
            $hoaDon = HoaDon::create([
                'ma_hoa_don' => $maHoaDon,
                'khach_hang_id' => $datBan->khach_hang_id,
                'tong_tien' => 0,
                'created_at' => now(),
                'updated_at' => now(),

            ]);

            // Tạo danh sách hóa đơn bàn
            $banAnIds = DatBan::where('thoi_gian_den', $datBan->thoi_gian_den)
                ->where('so_dien_thoai', $datBan->so_dien_thoai)
                ->where('created_at', $datBan->created_at)
                ->pluck('ban_an_id'); // Lấy danh sách ban_an_id

            // dd($banAnIds);
            foreach ($banAnIds as $banAnId) {
                HoaDonBan::create([
                    'ban_an_id' => $banAnId,
                    'hoa_don_id' => $hoaDon->id,
                    'trang_thai' => 'dang_xu_ly',
                ]);
                $banAn = BanAn::find($banAnId);
                $banAn->update(['trang_thai' => 'co_khach']);

                event(new BanAnUpdated($banAn)); // Phát sự kiện real-time
            }

            // Phát sự kiện khi đơn đặt bàn được cập nhật
            event(new DatBanCreated(datBan: $datBan));

            return redirect()->back()->with('success', 'Cập nhật thành công! Hóa đơn đã được tạo.');
        } else {
            return redirect()->back()->with('error', 'Không thể cập nhật, trạng thái không hợp lệ.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DatBan $datBan)
    {
        // Kiểm tra trạng thái của đơn đặt bàn trước khi thực hiện
        if ($datBan->trang_thai === 'dang_xu_ly') {
            // Cập nhật trạng thái 'da_huy' cho tất cả các đơn đặt bàn có cùng 'thoi_gian_den', 'so_dien_thoai' và 'created_at'
            DatBan::where('thoi_gian_den', $datBan->thoi_gian_den)
                ->where('so_dien_thoai', $datBan->so_dien_thoai)
                ->where('created_at', $datBan->created_at)
                ->update(['trang_thai' => 'da_huy']);

            // Phát sự kiện khi đơn đặt bàn bị hủy
            event(new DatBanCreated(datBan: $datBan));

            // Thông báo thành công
            return redirect()->back()->with('success', 'Tất cả các đơn đặt bàn đã được hủy thành công!');
        } else {
            // Nếu trạng thái không phải 'dang_xu_ly', thông báo lỗi
            return redirect()->back()->with('error', 'Không thể hủy, trạng thái không phải "Đang xử lý".');
        }
    }
}
