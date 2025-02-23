<?php

namespace App\Http\Controllers;

use App\Models\CaLam;
use App\Models\ChamCong;
use App\Http\Requests\StoreChamCongRequest;
use App\Http\Requests\UpdateChamCongRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\NhanVien; // Đảm bảo import model NhanVien
use Illuminate\Support\Facades\DB;

class ChamCongController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    Carbon::setLocale('vi'); // Đặt ngôn ngữ tiếng Việt
    // Lấy giá trị week_offset từ query, mặc định là 0 nếu không có
    $weekOffset = $request->query('week_offset', 0);

    // Lấy ngày đầu tuần (Thứ Hai)
    $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY)->addWeeks($weekOffset);

    // Lấy danh sách ngày trong tuần
    $dates = collect();
    for ($i = 0; $i < 7; $i++) {
        $dates->push($startOfWeek->copy()->addDays($i));
    }

    $weekLabel = "Tuần " . $startOfWeek->weekOfYear . " - Th." . $startOfWeek->format('m Y');

    // Lấy danh sách ca làm
    $caLams = CaLam::all();

    // Lấy danh sách chấm công với thông tin nhân viên
    $chamCongs = DB::table('ca_lam_nhan_viens')
    ->join('nhan_viens', 'ca_lam_nhan_viens.nhan_vien_id', '=', 'nhan_viens.id')
    ->join('ca_lams', 'ca_lam_nhan_viens.ca_lam_id', '=', 'ca_lams.id')
    ->leftJoin('cham_congs', function ($join) {
        $join->on('ca_lam_nhan_viens.nhan_vien_id', '=', 'cham_congs.nhan_vien_id')
              ->on('ca_lam_nhan_viens.ca_lam_id', '=', 'cham_congs.ca_lam_id') // ⚠️ THÊM điều kiện này
             ->on('ca_lam_nhan_viens.ngay_lam', '=', 'cham_congs.ngay_cham_cong'); // Đảm bảo nối đúng ngày
    })
    ->whereBetween('ca_lam_nhan_viens.ngay_lam', [
        $dates->first()->format('Y-m-d'),
        $dates->last()->format('Y-m-d')
    ])
    ->select(
        'ca_lam_nhan_viens.id AS ca_lam_nhan_vien_id', // ID của bảng trung gian
        'ca_lam_nhan_viens.ngay_lam',
        'ca_lam_nhan_viens.ca_lam_id',

        'nhan_viens.id AS nhan_vien_id', // ID của nhân viên
        'nhan_viens.ho_ten AS ten_nhan_vien', // Tên nhân viên

        'ca_lams.id AS ca_lam_id', // ID ca làm (để chắc chắn)
        'ca_lams.ten_ca AS ten_ca', // Tên ca làm
        'ca_lams.gio_bat_dau',
        'ca_lams.gio_ket_thuc',

        'cham_congs.id AS cham_cong_id', // ID chấm công (nếu có)
        'cham_congs.ngay_cham_cong', // Ngày chấm công
        'cham_congs.gio_vao_lam',
        'cham_congs.gio_ket_thuc',
        'cham_congs.mo_ta'
    )
    ->get();



    return view('admin.chamcong.chamcong', compact('dates', 'caLams', 'chamCongs', 'weekLabel', 'weekOffset'));
}

    
    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nhan_vien_id' => 'required|exists:nhan_viens,id',
            'ca_lam_id' => 'required|exists:ca_lams,id', // Bắt buộc phải có ca làm
            // 'ngay_cham_cong' => 'required|date',
            'gio_vao_lam' => 'required|date_format:H:i',
            'gio_ket_thuc' => 'required|date_format:H:i|after:gio_vao_lam',
            'mo_ta' => 'nullable|string'
        ]);
    
        // Kiểm tra nhân viên có thuộc ca làm đã chọn không
        $caLamHienTai = DB::table('ca_lam_nhan_viens')
            ->where('nhan_vien_id', $request->nhan_vien_id)
            ->where('ca_lam_id', $request->ca_lam_id)
            ->where('ngay_lam', $request->ngay_cham_cong)
            ->exists();
    
        if (!$caLamHienTai) {
            return back()->with('error', 'Không tìm thấy ca làm phù hợp cho nhân viên này!');
        }
    
        // Kiểm tra xem nhân viên đã chấm công cho **ca này** chưa
        $daChamCong = ChamCong::where('nhan_vien_id', $request->nhan_vien_id)
            ->where('ca_lam_id', $request->ca_lam_id) // Chỉ kiểm tra theo ca đã chọn
            ->where('ngay_cham_cong', $request->ngay_cham_cong)
            ->exists();
    
        if ($daChamCong) {
            return back()->with('error', 'Nhân viên đã chấm công ca làm này trong ngày!');
        }
    
        // Tạo bản ghi chấm công chỉ cho ca làm được chọn
        ChamCong::create([
            'nhan_vien_id' => $request->nhan_vien_id,
            'ca_lam_id' => $request->ca_lam_id, // Đảm bảo lưu đúng ca làm
            'ngay_cham_cong' => $request->ngay_cham_cong,
            'gio_vao_lam' => $request->gio_vao_lam,
            'gio_ket_thuc' => $request->gio_ket_thuc,
            'mo_ta' => $request->mo_ta,
        ]);
    
        return redirect()->route('cham-cong.index')->with('success', 'Chấm công thành công!');
    }
    
    

    
    

    


    /**
     * Display the specified resource.
     */
    public function show(ChamCong $chamCong)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nhan_vien_id, $ca_lam_id, $ngay_cham_cong)
    {
        $chamCong = ChamCong::where('nhan_vien_id', $nhan_vien_id)
                            ->where('ca_lam_id', $ca_lam_id)
                            ->where('ngay_cham_cong', $ngay_cham_cong)
                            ->first();
    
        // Nếu không có dữ liệu, tránh lỗi null
        $moTa = $chamCong->mo_ta ?? '';
        $gioVao = $chamCong->gio_vao_lam ?? '';
        $gioRa = $chamCong->gio_ket_thuc ?? '';
    
        return response()->make("
            <div class='mb-3'>
                <label for='modalGhiChu' class='form-label'>Ghi chú</label>
                <input type='text' class='form-control' id='modalGhiChu' value='" . e($moTa) . "'>
            </div>
            <div class='mb-3'>
                <label for='modalGioVao' class='form-label'>Giờ vào</label>
                <input type='time' class='form-control' id='modalGioVao' value='" . e($gioVao) . "'>
            </div>
            <div class='mb-3'>
                <label for='modalGioRa' class='form-label'>Giờ ra</label>
                <input type='time' class='form-control' id='modalGioRa' value='" . e($gioRa) . "'>
            </div>
        ", 200);
    }
    

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChamCong $chamCong)
    {
        //
    }
    public function getLichSuChamCong(Request $request)
    {
   

    $lichSu = ChamCong::orderBy('created_at', 'desc')->get(); // Lấy tất cả các cột

    if ($lichSu->isEmpty()) {
        return "<tr><td colspan='2'>Không có lịch sử chấm công</td></tr>";
    }

    $html = "";
    foreach ($lichSu as $item) {
        $thoiGian = Carbon::parse($item->created_at)->format('H:i d/m/Y'); 
        $html .= "<tr>
                    <td>{$thoiGian}</td>
                    <td>" . ($item->mo_ta ?? 'Không có mô tả') . "
                    </td>
                    <td>" . ($item->mo_ta ?? 'Không có mô tả') . "
                    </td>
                    <td>" . ($item->mo_ta ?? 'Không có mô tả') . "
                    </td>
                 </tr>";
    }

    return $html;
    }

    

}
