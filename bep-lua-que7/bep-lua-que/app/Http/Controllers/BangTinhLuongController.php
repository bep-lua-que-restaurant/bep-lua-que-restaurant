<?php


namespace App\Http\Controllers;

use App\Exports\BangLuongExport;
use App\Models\BangTinhLuong;
use App\Models\ChamCong;
use App\Models\NhanVien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Optional;
use Maatwebsite\Excel\Facades\Excel;

class BangTinhLuongController extends Controller
{
    // Lấy danh sách bảng lương
    public function index(Request $request)

{
    $query = BangTinhLuong::query()
    ->leftJoin('nhan_viens', 'bang_tinh_luongs.nhan_vien_id', '=', 'nhan_viens.id')
    ->leftJoin('cham_congs', function ($join) {
        $join->on('bang_tinh_luongs.nhan_vien_id', '=', 'cham_congs.nhan_vien_id')
            ->whereRaw("DATE_FORMAT(cham_congs.ngay_cham_cong, '%Y-%m') = DATE_FORMAT(bang_tinh_luongs.thang_nam, '%Y-%m')");
    })
    ->leftJoin('luongs', 'bang_tinh_luongs.nhan_vien_id', '=', 'luongs.nhan_vien_id')

    ->select(
        'bang_tinh_luongs.id',
        'bang_tinh_luongs.thang_nam',
        'bang_tinh_luongs.tong_luong',
        'nhan_viens.ho_ten as ten_nhan_vien',
        DB::raw('COUNT(cham_congs.id) as so_cong'),
        DB::raw('MAX(luongs.muc_luong) as muc_luong') // dùng MAX để lấy 1 giá trị nếu có nhiều
    )
    ->groupBy(
        'bang_tinh_luongs.id',
        'bang_tinh_luongs.thang_nam',
        'bang_tinh_luongs.tong_luong',
        'nhan_viens.ho_ten'
    );

    // 🔹 Lọc theo tháng và năm nếu có yêu cầu từ request
    if ($request->has('month') && $request->month != '') {
        $month = $request->month;
        $year = now()->year; // Lấy năm hiện tại

        $query->whereMonth('bang_tinh_luongs.thang_nam', $month)
              ->whereYear('bang_tinh_luongs.thang_nam', $year);
    }

    // 🔹 Lọc theo tên nhân viên nếu có yêu cầu
    if ($request->has('ten') && $request->ten != '') {
        $query->where('nhan_viens.ho_ten', 'like', '%' . $request->ten . '%');
    }

    // 🔹 Lấy dữ liệu phân trang
    $data = $query->latest('bang_tinh_luongs.id')->paginate(15);

    // Nếu là AJAX request, trả về partial view
    if ($request->ajax()) {
        return view('admin.bangluong.body-list', ['data' => $data])->render();
    }

    return view('admin.bangluong.list', [
        'data' => $data,
        'route' => route('luong.index'),
        'tableId' => 'list-container',
        'searchInputId' => 'search-name',
    ]);
}

    

    {
        $query = BangTinhLuong::query()
            ->leftJoin('nhan_viens', 'bang_tinh_luongs.nhan_vien_id', '=', 'nhan_viens.id')
            ->leftJoin('cham_congs', function ($join) {
                $join->on('bang_tinh_luongs.nhan_vien_id', '=', 'cham_congs.nhan_vien_id')
                    ->whereRaw("DATE_FORMAT(cham_congs.ngay_cham_cong, '%Y-%m') = DATE_FORMAT(bang_tinh_luongs.thang_nam, '%Y-%m')");
            })
            ->leftJoin('luongs', 'bang_tinh_luongs.nhan_vien_id', '=', 'luongs.nhan_vien_id')

            ->select(
                'bang_tinh_luongs.id',
                'bang_tinh_luongs.thang_nam',
                'bang_tinh_luongs.tong_luong',
                'nhan_viens.ho_ten as ten_nhan_vien',
                DB::raw('COUNT(cham_congs.id) as so_cong'),
                DB::raw('MAX(luongs.muc_luong) as muc_luong') // dùng MAX để lấy 1 giá trị nếu có nhiều
            )
            ->groupBy(
                'bang_tinh_luongs.id',
                'bang_tinh_luongs.thang_nam',
                'bang_tinh_luongs.tong_luong',
                'nhan_viens.ho_ten'
            );

        // 🔹 Lọc theo tháng và năm nếu có yêu cầu từ request
        if ($request->has('month') && $request->month != '') {
            $month = $request->month;
            $year = now()->year; // Lấy năm hiện tại

            $query->whereMonth('bang_tinh_luongs.thang_nam', $month)
                ->whereYear('bang_tinh_luongs.thang_nam', $year);
        }

        // 🔹 Lọc theo tên nhân viên nếu có yêu cầu
        if ($request->has('ten') && $request->ten != '') {
            $query->where('nhan_viens.ho_ten', 'like', '%' . $request->ten . '%');
        }

        // 🔹 Lấy dữ liệu phân trang
        $data = $query->latest('bang_tinh_luongs.id')->paginate(15);

        // Nếu là AJAX request, trả về partial view
        if ($request->ajax()) {
            return view('admin.bangluong.body-list', ['data' => $data])->render();
        }

        return view('admin.bangluong.list', [
            'data' => $data,
            'route' => route('luong.index'),
            'tableId' => 'list-container',
            'searchInputId' => 'search-name',
        ]);
    }




    // Hiển thị form tạo bảng lương
    public function create(Request $request)
    {

        // Lấy tháng từ request, mặc định là tháng hiện tại
        $thangChon = $request->input('thang', now()->format('Y-m'));
    
        // Lọc chỉ những nhân viên có chấm công trong tháng được chọn
        $nhanViens = NhanVien::whereHas('chamCongs', function ($query) use ($thangChon) {
            $query->whereYear('ngay_cham_cong', date('Y', strtotime($thangChon)))
                  ->whereMonth('ngay_cham_cong', date('m', strtotime($thangChon)));
        })
        ->with(['luong', 'chamCongs' => function ($query) use ($thangChon) {
            $query->whereYear('ngay_cham_cong', date('Y', strtotime($thangChon)))
                  ->whereMonth('ngay_cham_cong', date('m', strtotime($thangChon)))
                  ->with('caLam');
        }])
        ->get();
    

        $thangChon = $request->input('thang', now()->format('Y-m'));
        $ngayBatDauThang = Carbon::parse($thangChon . '-01');
        $ngayKetThucThang = $ngayBatDauThang->copy()->endOfMonth();
    
        // Lấy danh sách nhân viên có chấm công trong tháng đã chọn
        $nhanViens = NhanVien::whereHas('chamCongs', function ($query) use ($thangChon) {
            $query->whereYear('ngay_cham_cong', date('Y', strtotime($thangChon)))
                  ->whereMonth('ngay_cham_cong', date('m', strtotime($thangChon)));
        })->with([
            'luong',
            'chamCongs' => function ($query) use ($thangChon) {
                $query->whereYear('ngay_cham_cong', date('Y', strtotime($thangChon)))
                      ->whereMonth('ngay_cham_cong', date('m', strtotime($thangChon)))
                      ->with('caLam');
            }
        ])->get();
    
        foreach ($nhanViens as $nhanVien) {
            // Lấy tất cả bản lương của nhân viên, sắp xếp theo ngày áp dụng tăng dần
            $luongs = $nhanVien->luong()->orderBy('ngay_ap_dung', 'asc')->get();
    
            $luongCu = null;
            $luongMoi = null;
    
            foreach ($luongs as $luong) {
                $ngayApDung = Carbon::parse($luong->ngay_ap_dung);
    
                // Nếu ngày áp dụng <= cuối tháng => là lương cũ (đang áp dụng)
                if ($ngayApDung <= $ngayKetThucThang) {
                    $luongCu = $luong;
                }
    
                // Nếu ngày áp dụng > cuối tháng => là lương mới (áp dụng tháng sau)
                if ($ngayApDung > $ngayKetThucThang) {
                    $luongMoi = $luong;
                }
            }
    
            // Gán lại lương cũ và lương mới vào nhân viên
            $nhanVien->luong_cu = $luongCu ? $luongCu->muc_luong : 0;
            $nhanVien->luong_moi = $luongMoi ? $luongMoi->muc_luong : 0;
    
            // Nếu không có lương cũ nhưng có lương mới, dùng luôn lương mới cho cả 2
            if ($luongMoi !== null && $luongCu === null) {
                $nhanVien->luong_cu = $luongMoi->muc_luong;
                $nhanVien->luong_moi = $luongMoi->muc_luong;
                $nhanVien->hinh_thuc = $luongMoi->hinh_thuc;
            }
    
            // Ưu tiên lấy hình thức từ lương cũ, nếu không có thì lấy từ lương mới
            $nhanVien->hinh_thuc = $luongCu 
                ? $luongCu->hinh_thuc 
                : ($luongMoi ? $luongMoi->hinh_thuc : 'Theo ca');
        }
    
        // Trả về view tính lương

        return view('admin.bangluong.tinhluong', compact('nhanViens', 'thangChon'));
    }
    


    // Lưu bảng lương vào database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nhan_vien_id' => 'required|array',
            'nhan_vien_id.*' => 'exists:nhan_viens,id',
            'so_ca_lam' => 'required|array',
            'so_ngay_cong' => 'nullable|array',
            'tong_luong' => 'required|array',
            'thang_nam' => 'required|date_format:Y-m',
        ]);

    
        $thangNam = $request->thang_nam . '-01';
    
        if (BangTinhLuong::where('thang_nam', $thangNam)->exists()) {
            return redirect()->back()->with('error', "Lương tháng $request->thang_nam đã được chốt!");
        }
    
        foreach ($request->nhan_vien_id as $index => $nhan_vien_id) {
            $nhanVien = NhanVien::findOrFail($nhan_vien_id);    


        $thangNam = $request->thang_nam . '-01';

        if (BangTinhLuong::where('thang_nam', $thangNam)->exists()) {
            return redirect()->back()->with('error', "Lương tháng $request->thang_nam đã được chốt!");
        }

        foreach ($request->nhan_vien_id as $index => $nhan_vien_id) {
            $nhanVien = NhanVien::findOrFail($nhan_vien_id);

            $hinhThuc = optional($nhanVien->luong)->hinh_thuc ?? 'ca';
            $mucLuong = optional($nhanVien->luong)->muc_luong ?? 0;
            $soCaLam = $request->so_ca_lam[$index] ?? 0;
            $soNgayLam = $request->so_ngay_cong[$index] ?? 0;
            $tongLuong = $request->tong_luong[$index] ?? 0;

    
           // ✅ Thêm dòng này để lưu mức lương vào bảng lương
    BangTinhLuong::create([
        'nhan_vien_id' => $nhan_vien_id,
        'thang_nam' => $thangNam,
        'so_ca_lam' => $soCaLam,
        'so_ngay_cong' => $soNgayLam ?? 0,
        'tong_luong' => $tongLuong,
        'muc_luong' => $mucLuong, // ✅ thêm dòng này
    ]);
        }
    
        // ⏬ Xuất Excel ngay sau khi chốt xong
        return Excel::download(new BangLuongExport($request->thang_nam), 'BangLuong-' . $request->thang_nam . '.xlsx');
    }
        public function show($id)
    {
        $bangTinhLuong = DB::table('bang_tinh_luongs')
        ->join('nhan_viens', 'bang_tinh_luongs.nhan_vien_id', '=', 'nhan_viens.id') // Lấy tên nhân viên
        ->leftJoin('cham_congs', 'nhan_viens.id', '=', 'cham_congs.nhan_vien_id') // Lấy chấm công của nhân viên
        ->leftJoin('ca_lams', 'cham_congs.ca_lam_id', '=', 'ca_lams.id') // Lấy tên ca làm
        ->leftJoin('luongs', 'nhan_viens.id', '=', 'luongs.nhan_vien_id') // Lấy mức lương
        ->where('bang_tinh_luongs.id', $id)
        ->select(
            'bang_tinh_luongs.*',
            'nhan_viens.ho_ten as ten_nhan_vien',
            'ca_lams.ten_ca',
            'cham_congs.ngay_cham_cong',
            'luongs.muc_luong', // Lấy mức lương
            'bang_tinh_luongs.so_ca_lam',
            'bang_tinh_luongs.so_ngay_cong',
            'bang_tinh_luongs.tong_luong' // Lấy t��ng lương
            )
        ->get();


            // ✅ Thêm dòng này để lưu mức lương vào bảng lương
            BangTinhLuong::create([
                'nhan_vien_id' => $nhan_vien_id,
                'thang_nam' => $thangNam,
                'so_ca_lam' => $soCaLam,
                'so_ngay_cong' => $soNgayLam ?? 0,
                'tong_luong' => $tongLuong,
                'muc_luong' => $mucLuong, // ✅ thêm dòng này
            ]);
        }

        // ⏬ Xuất Excel ngay sau khi chốt xong
        return Excel::download(new BangLuongExport($request->thang_nam), 'BangLuong-' . $request->thang_nam . '.xlsx');
    }
    public function show($id)
    {
        $bangTinhLuong = DB::table('bang_tinh_luongs')
            ->join('nhan_viens', 'bang_tinh_luongs.nhan_vien_id', '=', 'nhan_viens.id') // Lấy tên nhân viên
            ->leftJoin('cham_congs', 'nhan_viens.id', '=', 'cham_congs.nhan_vien_id') // Lấy chấm công của nhân viên
            ->leftJoin('ca_lams', 'cham_congs.ca_lam_id', '=', 'ca_lams.id') // Lấy tên ca làm
            ->leftJoin('luongs', 'nhan_viens.id', '=', 'luongs.nhan_vien_id') // Lấy mức lương
            ->where('bang_tinh_luongs.id', $id)
            ->select(
                'bang_tinh_luongs.*',
                'nhan_viens.ho_ten as ten_nhan_vien',
                'ca_lams.ten_ca',
                'cham_congs.ngay_cham_cong',
                'luongs.muc_luong', // Lấy mức lương
                'bang_tinh_luongs.so_ca_lam',
                'bang_tinh_luongs.so_ngay_cong',
                'bang_tinh_luongs.tong_luong' // Lấy t��ng lương
            )
            ->get();


        return view('admin.bangluong.show', compact('bangTinhLuong'));
    }


    public function exportBangLuong()
    {
        // Xuất file Excel với tên "DanhMucMonAn.xlsx"
        return Excel::download(new BangLuongExport, 'BangLuong.xlsx');
    }

    

    
}


}

}

