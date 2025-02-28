<?php


namespace App\Http\Controllers;

use App\Models\BangTinhLuong;
use App\Models\ChamCong;
use App\Models\NhanVien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BangTinhLuongController extends Controller
{
    // Lấy danh sách bảng lương
    public function index(Request $request)
    {
        $query = BangTinhLuong::query()
    ->leftJoin('nhan_viens', 'bang_tinh_luongs.nhan_vien_id', '=', 'nhan_viens.id')
    ->leftJoin('cham_congs', 'bang_tinh_luongs.nhan_vien_id', '=', 'cham_congs.nhan_vien_id')
    ->leftJoin('ca_lams', 'cham_congs.ca_lam_id', '=', 'ca_lams.id')
    ->leftJoin('luongs', 'bang_tinh_luongs.nhan_vien_id', '=', 'luongs.nhan_vien_id')
    ->select(
        'bang_tinh_luongs.id',
        'bang_tinh_luongs.thang_nam',
        'bang_tinh_luongs.tong_luong',
        'nhan_viens.ho_ten as ten_nhan_vien'
    )
    ->groupBy('bang_tinh_luongs.id', 'bang_tinh_luongs.thang_nam', 'bang_tinh_luongs.tong_luong', 'nhan_viens.ho_ten');

    
        // Áp dụng bộ lọc nếu có
        if ($request->has('ten') && $request->ten != '') {
            $query->where('nhan_viens.ho_ten', 'like', '%' . $request->ten . '%');
        }
    
        // Lấy danh sách nhân viên sau khi đã lọc
        $nhanViens = $query->get();
    
        // Lấy dữ liệu phân trang
        $data = $query->latest('bang_tinh_luongs.id')->paginate(15);
    
        return view('admin.bangluong.list', [
            'data' => $data,
            'nhanViens' => $nhanViens, // Truyền danh sách nhân viên vào view
            'route' => route('luong.index'),
            'tableId' => 'list-container',
            'searchInputId' => 'search-name',
        ]);
    }
    

    // Hiển thị form tạo bảng lương
    public function create()
    {
        // $nhanViens = NhanVien::all();
        $nhanViens = NhanVien::with(['luong', 'chamCongs.caLam'])->get();
// return view('admin.bangluong.tinhluong', compact('nhanViens'));

//         $nhanVien = NhanVien::with('luong')->first();
// dd($nhanVien->toArray());

        return view('admin.bangluong.tinhluong', compact('nhanViens'));
    }

    // Lưu bảng lương vào database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nhan_vien_id' => 'required|array', // Danh sách nhân viên
            'nhan_vien_id.*' => 'exists:nhan_viens,id',
            'so_ca_lam' => 'required|array',
            'so_ngay_cong' => 'required|array',
            'tong_luong' => 'required|array',
        ]);
    
        foreach ($request->nhan_vien_id as $index => $nhan_vien_id) {
            $nhanVien = NhanVien::findOrFail($nhan_vien_id);
    
            // Kiểm tra hình thức tính lương
            $hinhThuc = optional($nhanVien->luong)->hinh_thuc ?? 'ca';
            $mucLuong = optional($nhanVien->luong)->muc_luong ?? 0;
    
            // Lấy số ca và số ngày làm từ form
            $soCaLam = $request->so_ca_lam[$index] ?? 0;
            $soNgayLam = $request->so_ngay_cong[$index] ?? 0;
            $tongLuong = $request->tong_luong[$index] ?? 0; // Nhận từ form
    
            // Lưu vào bảng bảng lương
            BangTinhLuong::create([
                'nhan_vien_id' => $nhan_vien_id,
               'thang_nam' => now()->startOfMonth()->toDateString(),
                'so_ca_lam' => $soCaLam,
                'so_ngay_cong' => $soNgayLam,
                'tong_luong' => $tongLuong,
            ]);
        }
    
        return Redirect()->route('luong.index')->with('success', 'Lương đã được tính!');
    }
    public function show( $id
    )
    {
        // $bangTinhLuong = DB::table('cham_congs')
        // ->join('nhan_viens', 'cham_congs.nhan_vien_id', '=', 'nhan_viens.id')
        // ->join('bang_tinh_luongs', 'nhan_viens.id', '=', 'bang_tinh_luongs.nhan_vien_id')
        // ->leftJoin('ca_lams', 'cham_congs.ca_lam_id', '=', 'ca_lams.id')
        // ->where('bang_tinh_luongs.id', $id)
        // ->select('cham_congs.*', 'nhan_viens.ho_ten as ten_nhan_vien', 'ca_lams.ten_ca')
        // ->get();
        // dd($id);
    // dd($bangTinhLuong);
    // $query = ChamCong::join('nhan_viens', 'cham_congs.nhan_vien_id', '=', 'nhan_viens.id')
    // ->join('bang_tinh_luongs', 'nhan_viens.id', '=', 'bang_tinh_luongs.nhan_vien_id')
    // ->leftJoin('ca_lams', 'cham_congs.ca_lam_id', '=', 'ca_lams.id')
    // ->where('bang_tinh_luongs.id', $id)
    // ->select('cham_congs.*', 'nhan_viens.ho_ten as ten_nhan_vien', 'ca_lams.ten_ca');

// In ra SQL
// dd($query->toSql(), $query->getBindings());

    
// dd($bangTinhLuong->nhanVien->chamCongs);
// dd($bangTinhLuong);
        // dd($bangTinhLuong->SchamCongs);


        return view('admin.bangluong.show', compact('bangTinhLuong'));
    }

    public function filter(Request $request)
    {
        $filter = $request->filter;
    $query = BangTinhLuong::query(); // Gọi query() trên Model

    switch ($filter) {
        case 'ngay':
            $query->whereDate('created_at', Carbon::today());
            break;
        case 'tuan':
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            break;
        case 'thang':
            $query->whereMonth('created_at', Carbon::now()->month);
            break;
        case 'nam':
            $query->whereYear('created_at', Carbon::now()->year);
            break;
    }

    $bangLuongs = $query->get();

        return view('admin.bangluong.list', compact('bangLuongs')); // Trả về bảng lương đã lọc
    }
}
