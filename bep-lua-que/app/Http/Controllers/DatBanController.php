<?php

namespace App\Http\Controllers;

use App\Events\DatBanCreated;
use App\Models\DatBan;
use App\Http\Requests\UpdateDatBanRequest;
use App\Models\BanAn;
// use Flasher\Laravel\Http\Request;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;




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
        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek()->endOfDay();
        $datBansWeek = DatBan::whereBetween('thoi_gian_den', [$startOfWeek, $endOfWeek])
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
            ->groupBy(
                'dat_bans.id',
                'dat_bans.thoi_gian_den',
                'khach_hangs.ho_ten',
                'khach_hangs.so_dien_thoai',
                'dat_bans.so_nguoi',
                'dat_bans.trang_thai',
                'dat_bans.mo_ta'
            )
            ->orderBy('dat_bans.id', 'desc') // Sắp xếp theo ID mới nhất
            ->paginate(10);

        return view('admin.datban.danhsach', compact('banhSachDatban'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function filterDatBan(Request $request)
    {
        $today = Carbon::today();

        // Nhận dữ liệu từ AJAX
        $search = $request->input('search');
        $trang_thai = $request->input('trang_thai');

        $query = DatBan::select(
            DB::raw('MIN(dat_bans.id) as id'), // Lấy ID nhỏ nhất để dùng cho Sửa, Xóa
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
            ->groupBy('dat_bans.thoi_gian_den', 'khach_hangs.ho_ten', 'khach_hangs.so_dien_thoai', 'dat_bans.so_nguoi', 'dat_bans.trang_thai', 'dat_bans.mo_ta')
            ->orderByRaw("CASE WHEN DATE(dat_bans.thoi_gian_den) = ? THEN 0 ELSE 1 END, dat_bans.thoi_gian_den ASC", [$today]);

        // Lọc theo từ khóa (họ tên hoặc số điện thoại)
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('khach_hangs.ho_ten', 'LIKE', "%{$search}%")
                    ->orWhere('khach_hangs.so_dien_thoai', 'LIKE', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if (!empty($trang_thai)) {
            $query->where('dat_bans.trang_thai', $trang_thai);
        }

        // Trả về dữ liệu dưới dạng JSON
        return response()->json($query->get());
    }

    public function create(Request $request)
    {
        // Lấy các tham số từ query parameters (như ban đầu)
        $tenBan = $request->query('ten_ban', 'Không xác định');
        $idBan = $request->query('id_ban', '');
        $time = $request->query('time', '08:00');
        $date = $request->query('date', now()->format('Y-m-d'));

        $thoiGianDen = $request->query('thoi_gian_den', "$date $time");

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
        // Lấy thời gian người dùng chọn từ input (đảm bảo rằng format đúng)
        $thoiGianDen = $request->input('thoi_gian_den');

        // Chuyển thời gian thành đối tượng Carbon
        $thoiGianDenCarbon = \Carbon\Carbon::parse($thoiGianDen);

        // Tính khoảng thời gian 1 giờ trước và 1 giờ sau thời gian nhập vào
        $startTime = $thoiGianDenCarbon->copy()->subHour();
        $endTime = $thoiGianDenCarbon->copy()->addHour();

        // Lấy các ban_an_id trong bảng dat_bans có thời gian đặt bàn trong khoảng +/- 1 giờ
        $datBans = DatBan::whereBetween('thoi_gian_den', [$startTime, $endTime])
            ->whereNull('deleted_at')  // Kiểm tra xem bảng có field deleted_at hay không
            ->pluck('ban_an_id')
            ->toArray();

        // Lọc các bàn ăn không bị trùng với thời gian đã có
        $banAns = BanAn::join('phong_ans', 'ban_ans.vi_tri', '=', 'phong_ans.id')
            ->whereNull('ban_ans.deleted_at')
            ->whereNull('phong_ans.deleted_at')
            ->whereNotIn('ban_ans.id', $datBans)  // Loại bỏ các bàn ăn đã có trong khoảng thời gian
            ->orderBy('ban_ans.vi_tri')
            ->orderBy('ban_ans.id')
            ->select('ban_ans.id', 'ban_ans.ten_ban', 'ban_ans.so_ghe', 'phong_ans.ten_phong_an')
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
        $request->validate([
            // Kiểm tra họ và tên khách hàng
            'customer_name' => 'required|string|max:255',  // Yêu cầu nhập, là chuỗi, tối đa 255 ký tự
            'customer_phone' => 'required|string|max:15',  // Yêu cầu nhập, là chuỗi, tối đa 15 ký tự
            'customer_cancuoc' => 'required|string|max:20',  // Yêu cầu nhập, là chuỗi, tối đa 20 ký tự
            'thoi_gian_den' => 'required|date|after_or_equal:today',  // Yêu cầu nhập, phải là ngày hợp lệ và phải sau hoặc bằng ngày hiện tại
            'num_people' => 'required|integer|min:1',  // Yêu cầu nhập, là số nguyên và tối thiểu là 1
            'ban_an_ids' => 'required|array|min:1',  // Yêu cầu chọn ít nhất một bàn ăn
        ], [
            // Thông báo lỗi tùy chỉnh cho từng trường

            'customer_name.required' => 'Họ và tên là bắt buộc.',
            'customer_name.string' => 'Họ và tên phải là chuỗi ký tự.',
            'customer_name.max' => 'Họ và tên không được vượt quá 255 ký tự.',

            'customer_phone.required' => 'Số điện thoại là bắt buộc.',
            'customer_phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'customer_phone.max' => 'Số điện thoại không được vượt quá 15 ký tự.',

            'customer_cancuoc.required' => 'Căn cước công dân là bắt buộc.',
            'customer_cancuoc.string' => 'Căn cước công dân phải là chuỗi ký tự.',
            'customer_cancuoc.max' => 'Căn cước công dân không được vượt quá 20 ký tự.',

            'thoi_gian_den.required' => 'Thời gian đến là bắt buộc.',
            'thoi_gian_den.date' => 'Thời gian đến phải là một ngày hợp lệ.',
            'thoi_gian_den.after_or_equal' => 'Thời gian đến không được sớm hơn ngày hiện tại.',

            'num_people.required' => 'Số người là bắt buộc.',
            'num_people.integer' => 'Số người phải là một số nguyên.',
            'num_people.min' => 'Số người phải lớn hơn hoặc bằng 1.',

            'ban_an_ids.required' => 'Bàn ăn là bắt buộc.',
            'ban_an_ids.array' => 'Bàn ăn phải là một mảng.',
            'ban_an_ids.min' => 'Cần chọn ít nhất một bàn ăn.',
        ]);

        // Kiểm tra khách hàng đã tồn tại
        $customer = KhachHang::where('so_dien_thoai', $request->customer_phone)
            ->where('can_cuoc', $request->customer_cancuoc)
            ->first();

        // Nếu không có khách hàng, tạo mới
        if (!$customer) {
            $customer = KhachHang::create([
                'ho_ten' => $request->customer_name,
                'so_dien_thoai' => $request->customer_phone,
                'email' => $request->customer_email,
                'can_cuoc' => $request->customer_cancuoc,
            ]);
        }

        // Lưu đơn đặt bàn
        foreach ($request->ban_an_ids as $banAnId) {
            $datBan = DatBan::create([
                'khach_hang_id' => $customer->id,
                'so_dien_thoai' => $customer->so_dien_thoai,
                'thoi_gian_den' => $request->thoi_gian_den,
                'mo_ta' => $request->mo_ta,
                'so_nguoi' => $request->num_people,
                'ban_an_id' => $banAnId,  // Lưu thông tin bàn ăn
            ]);

            // Phát sự kiện ngay sau khi tạo bản ghi
            // broadcast(new DatBanCreated($datBan))->toOthers();
            event(new DatBanCreated($datBan));
        }



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
            ->where('thoi_gian_den', $datBan->thoi_gian_den)
            ->with(['banAn', 'banAn.phongAn']) // Load các bàn và phòng
            ->get();

        // Trả về view với thông tin đặt bàn và bàn đã chọn
        return view('admin.datban.show', compact('datBan', 'datBans'));
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

    public function update(UpdateDatBanRequest $request, DatBan $datBan)
    {
        // Kiểm tra trạng thái hiện tại của đơn đặt bàn
        if ($datBan->trang_thai === 'dang_xu_ly') {
            // Cập nhật trạng thái sang 'xac_nhan' cho tất cả các đơn có cùng 'thoi_gian_den', 'so_dien_thoai', và 'created_at'
            DatBan::where('thoi_gian_den', $datBan->thoi_gian_den)
                ->where('so_dien_thoai', $datBan->so_dien_thoai)
                ->where('created_at', $datBan->created_at)
                ->update(['trang_thai' => 'xac_nhan']);
            // Phát sự kiện khi đơn đặt bàn được cập nhật

            // Thông báo thành công
            return redirect()->back()->with('success', 'Cập nhật trạng thái thành công! Trạng thái đã được chuyển sang "Đã xác nhận".');
        } else {
            // Nếu trạng thái không phải 'dang_xu_ly', thông báo lỗi
            return redirect()->back()->with('error', 'Không thể cập nhật, trạng thái hiện tại không phải là "Đang xử lý".');
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

            // Thông báo thành công
            return redirect()->back()->with('success', 'Tất cả các đơn đặt bàn đã được hủy thành công!');
        } else {
            // Nếu trạng thái không phải 'dang_xu_ly', thông báo lỗi
            return redirect()->back()->with('error', 'Không thể hủy, trạng thái không phải "Đang xử lý".');
        }
    }
}
