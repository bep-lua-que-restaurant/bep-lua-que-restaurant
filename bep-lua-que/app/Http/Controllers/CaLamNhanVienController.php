<?php

namespace App\Http\Controllers;

use App\Models\CaLamNhanVien;

namespace App\Http\Controllers;

use App\Exports\CaLamNhanVienExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\LichLamViec;
use App\Models\CaLam;
use App\Models\NhanVien;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LichLamViecExport;
use App\Models\CaLamNhanVien;

class CaLamNhanVienController extends Controller
{

    public function index(Request $request)
    {
        Carbon::setLocale('vi');
        $weekOffset = $request->query('week_offset', 0);

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY)->addWeeks($weekOffset);

        $dates = collect();
        for ($i = 0; $i < 7; $i++) {
            $dates->push($startOfWeek->copy()->addDays($i));
        }

        $weekLabel = "Tuần " . $startOfWeek->weekOfYear . " - Th." . $startOfWeek->format('m Y');

        // ✅ Query danh sách ca làm nhân viên
    $query = CaLamNhanVien::with(['caLam', 'nhanVien']);

    // 🔍 Tìm kiếm theo tên nhân viên
    if ($request->filled('search_nhanvien')) {
        $searchNhanVien = trim($request->search_nhanvien);
        $query->whereHas('nhanVien', function ($q) use ($searchNhanVien) {
            $q->where('ho_ten', 'like', "%$searchNhanVien%");
        });
    }

    // 🔍 Tìm kiếm theo ca làm
    if ($request->filled('search_ca')) {
        $query->where('ca_lam_id', $request->search_ca);
    }

    // 🔍 Tìm kiếm theo ngày làm
    if ($request->filled('search_ngaylam')) {
        $query->whereDate('ngay_lam', '=', $request->search_ngaylam);
    }

    // Lấy dữ liệu sau khi lọc
    $caLamNhanViens = $query->get();

    // Hiển thị thông báo khi không có kết quả
    if ($caLamNhanViens->isEmpty()) {
        return redirect()->route('ca-lam-nhan-vien.index')->with('error', 'Không tìm thấy kết quả nào!');
    }
    $caLams = CaLam::all();
    $nhanViens = NhanVien::all();


        // ✅ Sử dụng Eloquent Model với quan hệ
        $caLamNhanViens = CaLamNhanVien::with(['caLam', 'nhanVien'])->get();
        $caLams = CaLam::all();
        $nhanViens = NhanVien::all();

        return view('admin.caLamNhanVien.index')->with(compact('dates', 'caLams', 'caLamNhanViens', 'weekLabel', 'weekOffset', 'nhanViens'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'nhan_vien_id' => 'required|exists:nhan_viens,id',
            'ca_lam_id' => 'required|exists:ca_lams,id',
            'ngay_lam' => 'required|date',
            'gio_bat_dau' => 'nullable|date_format:H:i:s'
        ]);

        // Lấy thông tin giờ kết thúc từ bảng `ca_lams`
        $caLamInfo = CaLam::find($request->ca_lam_id);

        $caLam = new CaLamNhanVien();
        $caLam->nhan_vien_id = $request->nhan_vien_id;
        $caLam->ca_lam_id = $request->ca_lam_id;
        $caLam->ngay_lam = $request->ngay_lam;
        $caLam->gio_bat_dau = $request->gio_bat_dau ?? '08:00:00'; // Mặc định 08:00:00
        $caLam->gio_ket_thuc = $caLamInfo ? $caLamInfo->gio_ket_thuc : '17:00:00'; // Lấy giờ kết thúc từ bảng `ca_lams`
        $caLam->save();

        return redirect()->route('ca-lam-nhan-vien.index')->with('success', 'Thêm ca làm thành công');
    }


    public function edit($nhan_vien_id, $ca_lam_id, $ngay_lam)
    {
        $lichLamViec = LichLamViec::where('nhan_vien_id', $nhan_vien_id)
            ->where('ca_lam_id', $ca_lam_id)
            ->where('ngay_lam', $ngay_lam)
            ->first();

        $nhanViens = NhanVien::all(); // Thêm danh sách nhân viên vào đây

        return response()->make(view('admin.calamnhanvien.edit', compact('caLamNhanVien', 'nhanViens')));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nhan_vien_id' => 'required|exists:nhan_viens,id',
            'ca_lam_id' => 'required|exists:ca_lams,id',
            'ngay_lam' => 'required|date',
        ]);

        $caLamNhanVien = CaLamNhanVien::findOrFail($id);
        //dd($caLamNhanVien);
        $caLamNhanVien->update(attributes: [
            'nhan_vien_id' => $request->nhan_vien_id,
            'ca_lam_id' => $request->ca_lam_id,
            'ngay_lam' => $request->ngay_lam,
        ]);

        return redirect()->route('ca-lam-nhan-vien.index')->with('success', 'Cập nhật lịch làm việc thành công!');
    }

    public function export()
    {
        return Excel::download(new CaLamNhanVienExport, 'CaLamNhanVien.xlsx');
    }



    public function registerShift(Request $request)
    {
        $request->validate([
            'nhan_vien_id' => 'required|exists:nhan_viens,id',
            'ca_lam_id' => 'required|exists:ca_lams,id',
            'ngay_lam' => 'required|date',
        ]);

        LichLamViec::create($request->all());
        return back()->with('success', 'Đăng ký ca làm việc thành công!');
    }

    public function requestLeave(Request $request)
    {
        $request->validate([
            'nhan_vien_id' => 'required|exists:nhan_viens,id',
            'ngay_nghi' => 'required|date',
            'ly_do' => 'required|string'
        ]);

        DB::table('xin_nghi')->insert($request->only(['nhan_vien_id', 'ngay_nghi', 'ly_do']));
        return back()->with('success', 'Yêu cầu nghỉ đã được gửi!');
    }

    public function confirmShift(Request $request)
    {
        $request->validate([
            'ca_lam_nhan_vien_id' => 'required|exists:ca_lam_nhan_viens,id'
        ]);

        DB::table('ca_lam_nhan_viens')->where('id', $request->ca_lam_nhan_vien_id)->update(['xac_nhan' => 1]);
        return back()->with('success', 'Lịch làm việc đã được xác nhận!');
    }

    public function doiCa(Request $request, $id)
    {
        $request->validate([
            'ca_lam_moi_id' => 'required|exists:ca_lams,id',
        ]);


        // Lấy thông tin ca làm hiện tại
        $caLamNhanVien = CaLamNhanVien::findOrFail($id);

        // Kiểm tra xem trạng thái có phải "Chờ duyệt" không
        if (trim($caLamNhanVien->trang_thai) !== 'Chờ duyệt') {
            return back()->with('error', 'Chỉ có thể đổi ca khi trạng thái là "Chờ duyệt".');
        }

        // Lấy thông tin ca làm mới
        $caLamMoi = CaLam::findOrFail($request->ca_lam_moi_id);

        // Cập nhật ca làm mới
        $caLamNhanVien->update([
            'ca_lam_id' => $request->ca_lam_moi_id,
            'gio_bat_dau' => $caLamMoi->gio_bat_dau,
            'gio_ket_thuc' => $caLamMoi->gio_ket_thuc,
            'trang_thai' => 'Đã đổi ca', // Cập nhật trạng thái
        ]);

        return redirect()->route('ca-lam-nhan-vien.index')->with('success', 'Đổi ca làm thành công!');
    }
    // ///xóa ca làm cho nhân viên 
    // public function destroy($id)
    // {
    //     $caLamNhanVien = CaLamNhanVien::find($id);

    //     if (!$caLamNhanVien) {
    //         return redirect()->back()->with('error', 'Ca làm không tồn tại.');
    //     }

    //     $caLamNhanVien->delete();

    //     return redirect()->back()->with('success', 'Xóa ca làm thành công.');
    // }

    public function destroy($id)
    {
        $caLamNhanVien = CaLamNhanVien::find($id);

        if (!$caLamNhanVien) {
            return redirect()->back()->with('error', 'Ca làm không tồn tại.');
        }

        $caLamNhanVien->delete();

        return redirect()->back()->with('success', 'Xóa ca làm thành công.');
    }
}
