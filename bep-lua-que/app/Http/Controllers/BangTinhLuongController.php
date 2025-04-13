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

    

    // Hiển thị form tạo bảng lương
    public function create(Request $request)
    {
        
    
        // Kiểm tra kết quả
        // dd($nhanViens);
    
        return view('admin.bangluong.tinhluong', compact('nhanViens', 'thangChon'));
    }
    
    

    // Lưu bảng lương vào database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nhan_vien_id' => 'required|array', // Danh sách nhân viên
            'nhan_vien_id.*' => 'exists:nhan_viens,id',
            'so_ca_lam' => 'required|array',
            'so_ngay_cong' => 'nullable|array',
            'tong_luong' => 'required|array',
            'thang_nam' => 'required|date_format:Y-m',
        ]);
    
        // Lấy tháng cần chốt từ form
        $thangNam = $request->thang_nam . '-01'; // Chuyển về ngày đầu tháng
    
        // Kiểm tra xem lương tháng này đã được chốt chưa
        if (BangTinhLuong::where('thang_nam', $thangNam)->exists()) {
            return redirect()->back()->with('error', "Lương tháng $request->thang_nam đã được chốt!");
        }
    
        foreach ($request->nhan_vien_id as $index => $nhan_vien_id) {
            $nhanVien = NhanVien::findOrFail($nhan_vien_id);    
            // Kiểm tra hình thức tính lương
            $hinhThuc = optional($nhanVien->luong)->hinh_thuc ?? 'ca';
            $mucLuong = optional($nhanVien->luong)->muc_luong ?? 0;
    
            // Lấy số ca và số ngày làm từ form
            $soCaLam = $request->so_ca_lam[$index] ?? 0;
            // $soNgayLam = $request->so_ngay_cong[$index] ?? 0;
            $tongLuong = $request->tong_luong[$index] ?? 0; // Nhận từ form
    
            // Lưu vào bảng lương
            BangTinhLuong::create([
                'nhan_vien_id' => $nhan_vien_id,
                'thang_nam' => $thangNam,
                'so_ca_lam' => $soCaLam,
                'so_ngay_cong' => $soNgayLam ?? 0,
                'tong_luong' => $tongLuong,
            ]);
        }
    
        return redirect()->route('luong.index', ['thang' => $request->thang_nam])
                         ->with('success', "Lương tháng $request->thang_nam đã được chốt!");
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

    public function exportBangLuong(Request $request)
    {
        $month = $request->query('month', now()->month); // Lấy tháng được chọn
        $year = now()->year; // Bạn cũng có thể cho chọn năm nếu muốn
    
        return Excel::download(new BangLuongExport($month, $year), 'BangLuong-Thang-' . $month . '.xlsx');
    }
    
    
}

