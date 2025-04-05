<?php

namespace App\Http\Controllers;

use App\Events\BanAnUpdated;
use App\Events\DatBanCreated;
use App\Events\DatBanDeleted;
use App\Events\DatBanUpdated;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class DatBanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = \Carbon\Carbon::today();
        return view('gdnhanvien.datban.index', compact('today'));
    }

    public function indexNgay()
    {
        // $today = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        $today = \Carbon\Carbon::today();
        return view('gdnhanvien.datban.indexngay', compact('today'));
    }


    public function getDatBan($maDatBan)
    {
        $datBans = DatBan::where('ma_dat_ban', $maDatBan)->with('banAn')->get();

        if ($datBans->isEmpty()) {
            return response()->json(['message' => 'Không tìm thấy đặt bàn'], 404);
        }

        return response()->json([
            'ho_ten' => $datBans->first()->khachHang->ho_ten ?? null, // Lấy thông tin khách hàng từ dòng đầu tiên
            'so_dien_thoai' => $datBans->first()->so_dien_thoai,
            'so_nguoi' => $datBans->sum('so_nguoi'), // Tổng số người từ tất cả các dòng
            'mo_ta' => $datBans->first()->mo_ta,
            'ban_ans' => $datBans->pluck('banAn.ten_ban')->toArray(), // Lấy danh sách tên bàn
        ]);
    }


    public function getDatBanByDate(Request $request)
    {
        $date = $request->input('date', Carbon::now('Asia/Ho_Chi_Minh')->toDateString());

        // Lấy danh sách bàn (PHÂN TRANG 10 bàn/trang)
        $banPhong = BanAn::whereNull('deleted_at')
            ->orderBy('id')
            ->get(); // Lấy toàn bộ bàn ăn
        // ->paginate(10); // Sử dụng phân trang

        // Lấy danh sách đặt bàn theo ngày, loại bỏ trạng thái 'da_huy' và 'da_thanh_toan'
        $datBans = DatBan::whereDate('thoi_gian_den', $date)
            ->whereIn('ban_an_id', $banPhong->pluck('id'))
            ->whereNull('deleted_at')
            ->whereNotIn('trang_thai', ['da_huy', 'da_thanh_toan']) // Loại bỏ trạng thái không mong muốn
            ->get();

        return response()->json([
            'banPhong' => $banPhong, // Trả về danh sách có phân trang
            'datBans' => $datBans
        ]);
    }



        public function DanhSach()
        {
            $today = Carbon::today()->toDateString();

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
                ->groupBy(
                    'dat_bans.ma_dat_ban',
                    'dat_bans.thoi_gian_den',
                    'khach_hangs.id',
                    'khach_hangs.ho_ten',
                    'khach_hangs.so_dien_thoai',
                    'dat_bans.so_nguoi',
                    'dat_bans.trang_thai',
                    'dat_bans.mo_ta'
                )
                ->orderByRaw("
        CASE 
            WHEN dat_bans.trang_thai = 'dang_xu_ly' AND DATE(dat_bans.thoi_gian_den) = ? THEN 0 
            ELSE 1 
        END,
        dat_bans.thoi_gian_den DESC
    ", [$today])
                ->paginate(10);

            return view('gdnhanvien.datban.danhsach', compact('banhSachDatban', 'today'));
        }


    /**
     * Show the form for creating a new resource.
     */


    // public function DanhSach()
    // {
    //     $today = Carbon::today()->toDateString();

    //     $banhSachDatban = DatBan::onlyTrashed() // Chỉ lấy bản ghi đã bị xóa mềm
    //         ->select(
    //             'dat_bans.ma_dat_ban',
    //             'dat_bans.thoi_gian_den',
    //             'khach_hangs.id as khach_hang_id',
    //             'khach_hangs.ho_ten',
    //             'khach_hangs.so_dien_thoai',
    //             'dat_bans.so_nguoi',
    //             DB::raw("GROUP_CONCAT(DISTINCT ban_ans.ten_ban ORDER BY ban_ans.ten_ban SEPARATOR ', ') as danh_sach_ban"),
    //             'dat_bans.trang_thai',
    //             'dat_bans.mo_ta'
    //         )
    //         ->join('khach_hangs', 'dat_bans.khach_hang_id', '=', 'khach_hangs.id')
    //         ->join('ban_ans', 'dat_bans.ban_an_id', '=', 'ban_ans.id')
    //         ->groupBy(
    //             'dat_bans.ma_dat_ban',
    //             'dat_bans.thoi_gian_den',
    //             'khach_hangs.id',
    //             'khach_hangs.ho_ten',
    //             'khach_hangs.so_dien_thoai',
    //             'dat_bans.so_nguoi',
    //             'dat_bans.trang_thai',
    //             'dat_bans.mo_ta'
    //         )
    //         ->orderByRaw("
    //         CASE 
    //             WHEN dat_bans.trang_thai = 'dang_xu_ly' AND DATE(dat_bans.thoi_gian_den) = ? THEN 0 
    //             ELSE 1 
    //         END,
    //         dat_bans.thoi_gian_den DESC
    //     ", [$today])
    //         ->paginate(10);

    //     return view('gdnhanvien.datban.danhsach', compact('banhSachDatban', 'today'));
    // }

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

        return view('gdnhanvien.datban.create', compact('tenBan', 'idBan', 'time', 'date', 'banAns', 'thoiGianDen'));
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
        // dd($request->mo_ta);
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'required|string|max:20',
            'num_people'     => 'required|integer|min:1',
            'selectedIds'    => 'required|array',
            'thoi_gian_den'  => 'required|date_format:Y-m-d H:i:s',
            'gio_du_kien'    => 'required|date_format:H:i:s',
        ]);

        DB::beginTransaction(); // Bắt đầu transaction

        try {
            // Kiểm tra khách hàng
            $customer = KhachHang::firstOrCreate(
                ['so_dien_thoai' => $request->customer_phone],
                ['ho_ten' => $request->customer_name, 'email' => $request->customer_email]
            );

            // Tạo mã đặt bàn duy nhất
            $maDatBan = DatBan::generateMaDatBan();

            // Khởi tạo danh sách đặt bàn
            $danhSachBanDat = [];
            $tenBanList[] = [];
            $banAnIds = $request->selectedIds;

            // Lấy danh sách bàn ăn cần cập nhật
            $banAnList = BanAn::whereIn('id', $banAnIds)->get()->keyBy('id');

            foreach ($banAnIds as $banAnId) {
                $datBan = DatBan::create([
                    'khach_hang_id' => $customer->id,
                    'ho_ten' => $customer->ho_ten,
                    'so_dien_thoai' => $customer->so_dien_thoai,
                    'thoi_gian_den' => $request->thoi_gian_den,
                    'gio_du_kien'   => $request->gio_du_kien,
                    'mo_ta'         => $request->mo_ta,
                    'so_nguoi'      => $request->num_people,
                    'ban_an_id'     => $banAnId,
                    'ma_dat_ban'    => $maDatBan,
                    'trang_thai'    => 'dang_xu_ly',
                ]);

                $danhSachBanDat[] = $datBan;
                $tenBanList[] = $banAnList[$banAnId]->ten_ban ?? 'Không xác định';
                // Cập nhật trạng thái bàn ăn
                if (isset($banAnList[$banAnId])) {
                    $banAnList[$banAnId]->update(['trang_thai' => 'da_dat_truoc']);
                }
            }

            // Sau khi kết thúc vòng lặp, thêm tên bàn vào từng đặt bàn
            foreach ($danhSachBanDat as $key => $datBan) {
                $banAnId = $datBan->ban_an_id;

                // Lấy tên bàn dựa vào ban_an_id
                $tenBan = isset($banAnList[$banAnId]) ? $banAnList[$banAnId]->ten_ban : 'Không xác định';

                // Gán lại giá trị mới vào danh sách đặt bàn
                $danhSachBanDat[$key] = (object) array_merge($datBan->toArray(), ['ten_ban' => $tenBan]);
            }


            // Sau khi xử lý hết các bàn đặt
            event(new DatBanCreated($danhSachBanDat, $customer));


            // Phát sự kiện cập nhật bàn ăn (1 lần, tránh spam event)
            foreach ($banAnList as $banAn) {
                event(new BanAnUpdated($banAn));
            }

            // Gửi email xác nhận đặt bàn (1 lần, không lặp trong vòng lặp)
            if (!empty($customer->email)) {
                // Mail::to($customer->email)->send(new DatBanMail($customer, $danhSachBanDat));
                Mail::to($customer->email)->queue(new DatBanMail($customer, $danhSachBanDat));
            }

            DB::commit(); // Xác nhận transaction

            return response()->json(['message' => 'Đặt bàn thành công!'], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Hoàn tác nếu lỗi xảy ra
            return response()->json(['error' => 'Đặt bàn thất bại!', 'message' => $e->getMessage()], 500);
        }
    }


    public function show($maDatBan)
    {
        // Lấy thông tin đặt bàn dựa trên mã đặt bàn
        $datBans = DatBan::where('ma_dat_ban', $maDatBan)
            ->with(['khachHang', 'banAn']) // Load thêm thông tin khách hàng, bàn ăn và phòng ăn
            ->get();

        // Kiểm tra nếu không tìm thấy đặt bàn
        if ($datBans->isEmpty()) {
            return response()->json([
                'error' => true,
                'message' => 'Không tìm thấy đặt bàn!',
            ], 404);
        }

        // Lấy thông tin đặt bàn đầu tiên trong danh sách
        $datBan = $datBans->first();

        // Trả về dữ liệu JSON
        // return response()->json([
        //     'error' => false,
        //     'datBan' => $datBan,
        //     'datBans' => $datBans,
        // ]);
        return view('gdnhanvien.datban.show', compact('datBan', 'datBans'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($maDatBan)
    {
        // Lấy thông tin đặt bàn chính
        $datBan = DatBan::with('khachHang')->where('ma_dat_ban', $maDatBan)->first();

        $customer = KhachHang::find($datBan->khach_hang_id);

        if (!$datBan) {
            return redirect()->route('dat-ban.index')->with('error', 'Không tìm thấy đặt bàn!');
        }

        // Lấy tất cả bàn ăn để hiển thị
        // $banAns = BanAn::whereNull('deleted_at')->paginate(10);
        $banAns = BanAn::whereNull('deleted_at')->get();

        // Lấy các bàn của đơn đặt hiện tại (bàn đang được chỉnh sửa)
        $datBanCurrent = DatBan::where('ma_dat_ban', $maDatBan)
            ->get();

        // Lấy tất cả các đơn đặt bàn, trừ ma_dat_ban hiện tại
        $datBansOther = DatBan::where('ma_dat_ban', '!=', $maDatBan)
            ->whereIn('trang_thai', ['dang_xu_ly', 'xac_nhan'])
            ->get();

        // Truyền dữ liệu vào view
        return view('gdnhanvien.datban.edit', compact('datBan', 'banAns', 'datBanCurrent', 'datBansOther', 'maDatBan', 'customer'));
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
        DB::beginTransaction(); // Bắt đầu transaction

        try {

            // Xóa đơn đặt bàn cũ
            DatBan::where('ma_dat_ban', $maDatBan)->forceDelete();
            event(new DatBanDeleted(maDatBan: $maDatBan));

            // Xử lý thời gian đúng định dạng
            $thoiGianDen = Carbon::parse($request->thoi_gian_den)->format('Y-m-d H:i:s');
            $gioDuKien = Carbon::parse($request->gio_du_kien)->format('H:i:s');

            // Chuyển danh sách bàn từ chuỗi thành mảng
            $banAnIds = explode(',', $request->ban_an_ids);
            $danhSachBanDat = [];

            // Lấy danh sách bàn ăn cần cập nhật
            $banAnList = BanAn::whereIn('id', $banAnIds)->get()->keyBy('id');

            // Tạo mới đơn đặt bàn cho từng bàn ăn
            foreach ($banAnIds as $banAnId) {
                $datBan = DatBan::create([
                    'ma_dat_ban'     => $maDatBan,
                    'ban_an_id'      => $banAnId,
                    'thoi_gian_den'  => $thoiGianDen,
                    'gio_du_kien'    => $gioDuKien,
                    'khach_hang_id'  => $request->khach_hang_id,
                    'so_dien_thoai'  => $request->so_dien_thoai,
                    'so_nguoi'       => $request->so_nguoi,
                    'mo_ta'          => $request->mo_ta,
                    'trang_thai'     => 'xac_nhan',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                $danhSachBanDat[] = $datBan;
            }


            // Sau khi kết thúc vòng lặp, thêm tên bàn vào từng đặt bàn
            foreach ($danhSachBanDat as $key => $datBan) {
                $banAnId = $datBan->ban_an_id;

                // Lấy tên bàn dựa vào ban_an_id
                $tenBan = isset($banAnList[$banAnId]) ? $banAnList[$banAnId]->ten_ban : 'Không xác định';

                // Gán lại giá trị mới vào danh sách đặt bàn
                $danhSachBanDat[$key] = (object) array_merge($datBan->toArray(), ['ten_ban' => $tenBan]);
            }


            // Tạo object chứa đầy đủ thông tin để gửi qua sự kiện
            $customer = [
                'ho_ten'        => $request->ho_ten,
                'so_dien_thoai' => $request->so_dien_thoai,
                'khach_hang_id' => $request->khach_hang_id
            ];


            event(new DatBanUpdated($danhSachBanDat, $customer)); // ✅ Phát sự kiện với đủ dữ 

            // Tạo hóa đơn mới
            $maHoaDon = $this->generateMaHoaDon();
            $hoaDon = HoaDon::create([
                'ma_hoa_don'    => $maHoaDon,
                'ma_dat_ban'    => $maDatBan,
                'khach_hang_id' => $request->khach_hang_id,
                'tong_tien'     => 0,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // Tạo danh sách hóa đơn bàn
            foreach ($banAnIds as $banAnId) {
                HoaDonBan::create([
                    'ban_an_id' => $banAnId,
                    'hoa_don_id' => $hoaDon->id,
                    'trang_thai' => 'dang_xu_ly',
                ]);

                // Cập nhật trạng thái bàn ăn
                if (isset($banAnList[$banAnId])) {
                    $banAnList[$banAnId]->update(['trang_thai' => 'co_khach']);
                    event(new BanAnUpdated($banAnList[$banAnId]));
                }
            }

            DB::commit(); // Xác nhận transaction

            // return redirect()->back()->with('success', 'Cập nhật thành công! Hóa đơn đã được tạo.');
            $today = \Carbon\Carbon::today();
            return view('gdnhanvien.datban.index', compact('today'));
        } catch (\Exception $e) {
            DB::rollBack(); // Hoàn tác nếu lỗi xảy ra
            return redirect()->back()->with('error', 'Cập nhật thất bại: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($maDatBan)
    {
        // Kiểm tra đơn đặt bàn
        $datBan = DatBan::where('ma_dat_ban', $maDatBan)->first();
        if (!$datBan) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn đặt bàn!');
        }

        // Lấy danh sách bàn ăn liên quan
        $banAnIds = DatBan::where('ma_dat_ban', $maDatBan)->pluck('ban_an_id');
        $banAnList = BanAn::whereIn('id', $banAnIds)->get();

        // Cập nhật trạng thái bàn ăn & phát sự kiện
        foreach ($banAnList as $banAn) {
            if ($banAn->trang_thai !== 'trong') {
                $banAn->update(['trang_thai' => 'trong']);
                event(new BanAnUpdated($banAn)); // Phát sự kiện cập nhật bàn ăn
            }
        }

        // Cập nhật trạng thái đơn đặt bàn thành 'da_huy'
        DatBan::where('ma_dat_ban', $maDatBan)
            ->where('trang_thai', 'dang_xu_ly')
            ->update(['trang_thai' => 'da_huy']);

        // 🛑 Đảm bảo sự kiện được phát ra
        event(new DatBanDeleted(maDatBan: $maDatBan));
        // \Log::info("🚀 Sự kiện DatBanDeleted đã được phát", ['ma_dat_ban' => $maDatBan]);

        return redirect()->back()->with('success', 'Đơn đặt bàn đã được hủy thành công!');
    }
}
