<?php

namespace App\Http\Controllers;

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

class LichLamViecController extends Controller
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

        $caLams = CaLam::all();
        $nhanViens = NhanVien::all(); // Thêm danh sách nhân viên vào đây

        $lichLamViecs = DB::table('ca_lam_nhan_viens')
            ->join('nhan_viens', 'ca_lam_nhan_viens.nhan_vien_id', '=', 'nhan_viens.id')
            ->join('ca_lams', 'ca_lam_nhan_viens.ca_lam_id', '=', 'ca_lams.id')
            ->whereBetween('ca_lam_nhan_viens.ngay_lam', [
                $dates->first()->format('Y-m-d'),
                $dates->last()->format('Y-m-d')
            ])
            ->select(
                'ca_lam_nhan_viens.id AS ca_lam_nhan_vien_id',
                'ca_lam_nhan_viens.ngay_lam',
                'ca_lam_nhan_viens.ca_lam_id',
                'nhan_viens.id AS nhan_vien_id',
                'nhan_viens.ho_ten AS ten_nhan_vien',
                'ca_lams.id AS ca_lam_id',
                'ca_lams.ten_ca AS ten_ca',
                'ca_lams.gio_bat_dau',
                'ca_lams.gio_ket_thuc'
            )
            ->get();

        return view('admin.lichlamviec.index', compact('dates', 'caLams', 'lichLamViecs', 'weekLabel', 'weekOffset', 'nhanViens'));
    }

    public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'nhan_vien_id' => 'required|exists:nhan_viens,id',
            'ca_lam_id' => 'required|exists:ca_lams,id',
            'ngay_lam' => 'required|date',
        ]);

        // Chèn vào bảng `ca_lam_nhan_viens` thay vì `nhan_viens`
        $caLam = new CaLamNhanVien();
        $caLam->nhan_vien_id = $request->nhan_vien_id;
        $caLam->ca_lam_id = $request->ca_lam_id;
        $caLam->ngay_lam = $request->ngay_lam;
        $caLam->gio_bat_dau = $request->gio_bat_dau ?? '08:00:00'; // Nếu không có, mặc định 08:00:00
        $caLam->save();

        // Trả về thông báo thành công
        return redirect()->route('lich-lam-viec.index')->with('success', 'Thêm ca làm việc thành công!');
    }


    public function edit($nhan_vien_id, $ca_lam_id, $ngay_lam)
    {
        $lichLamViec = LichLamViec::where('nhan_vien_id', $nhan_vien_id)
            ->where('ca_lam_id', $ca_lam_id)
            ->where('ngay_lam', $ngay_lam)
            ->first();

        $nhanViens = NhanVien::all(); // Thêm danh sách nhân viên vào đây

        return response()->make(view('admin.lichlamviec.edit', compact('lichLamViec', 'nhanViens')));
    }

    public function update(Request $request, $nhan_vien_id, $ca_lam_id, $ngay_lam)
    {
        $request->validate([
            'nhan_vien_id' => 'required|exists:nhan_viens,id',
        ]);

        $lichLamViec = LichLamViec::where('nhan_vien_id', $nhan_vien_id)
            ->where('ca_lam_id', $ca_lam_id)
            ->where('ngay_lam', $ngay_lam)
            ->first();

        if ($lichLamViec) {
            $lichLamViec->update($request->only(['nhan_vien_id', 'ca_lam_id', 'ngay_lam']));
            return redirect()->route('lich-lam-viec.index')->with('success', 'Cập nhật lịch làm việc thành công!');
        }

        return back()->with('error', 'Không tìm thấy lịch làm việc để cập nhật!');
    }

    public function export()
    {
        return Excel::download(new LichLamViecExport, 'lich_lam_viec.xlsx');
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
}
