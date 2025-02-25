<?php

namespace App\Http\Controllers;

use App\Models\CaLam;
use App\Models\ChamCong;
use App\Http\Requests\StoreChamCongRequest;
use App\Http\Requests\UpdateChamCongRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\NhanVien; // Đảm bảo import model NhanVien


class ChamCongController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Carbon::setLocale('vi'); // Đặt ngôn ngữ tiếng Việt
        $weekOffset = $request->query('week', 0);
        $currentDate = Carbon::now();

        $currentWeek = $currentDate->copy()->addWeeks($weekOffset)->weekOfYear;
        $year = $currentDate->year;

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY)->addWeeks($weekOffset);

        // Tạo danh sách ngày từ Thứ 2 - Chủ nhật
        $dates = collect();
        for ($i = 0; $i < 7; $i++) {
            $dates->push($startOfWeek->copy()->addDays($i));
        }

        $weekLabel = "Tuần $currentWeek - Th." . $startOfWeek->format('m Y');

        // Lấy dữ liệu ca làm và chấm công
        $caLams = CaLam::all();
        $chamCongs = ChamCong::with('nhanVien')->orderBy('ngay_cham_cong')->get();
        // dd($chamCongs->toArray());
        $nhanViens = NhanVien::all();
        // dd($nhanViens->toArray());


        return view('admin.lichlamviec.index', compact('dates', 'caLams', 'chamCongs', 'weekLabel', 'currentWeek', 'year', 'nhanViens'));
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
            'ngay_cham_cong' => 'required|date',
            'ca_id' => 'required|exists:ca_lams,id',
        ]);

        $caLam = CaLam::find($request->ca_id);
        $nhanVien = NhanVien::find($request->nhan_vien_id);

        if (!$caLam || !$nhanVien) {
            return back()->with('error', 'Dữ liệu không hợp lệ!');
        }

        ChamCong::create([
            'nhan_vien_id' => $request->nhan_vien_id,
            'ngay_cham_cong' => $request->ngay_cham_cong,
            'gio_vao_lam' => $caLam->gio_bat_dau,
            'gio_ket_thuc' => $caLam->gio_ket_thuc,
            'mo_ta' => 'Chấm công tự động',
        ]);

        return redirect()->route('admin.chamcong.index')->with('success', 'Chấm công thành công!');
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
    public function edit(ChamCong $chamCong)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChamCongRequest $request, ChamCong $chamCong)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChamCong $chamCong)
    {
        //
    }
}
