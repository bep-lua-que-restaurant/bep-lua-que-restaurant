<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Exports\ChamCongExport;
use App\Models\CaLam;
use App\Models\CaLamNhanVien;
use App\Models\ChamCong;
use App\Http\Requests\StoreChamCongRequest;
use App\Http\Requests\UpdateChamCongRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\NhanVien; // Đảm bảo import model NhanVien
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ChamCongController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request)
     {
         Carbon::setLocale('vi');

         $selectedMonth = $request->query('selected_month', Carbon::now()->format('Y-m'));
         $selectedDate = Carbon::parse($selectedMonth . '-01');

         $firstDayOfMonth = $selectedDate->copy()->startOfMonth();
         $lastDayOfMonth = $selectedDate->copy()->endOfMonth();

         $dates = collect();

         // Đây nè: dùng biến tạm riêng $day, không thay đổi $firstDayOfMonth
         for ($day = $firstDayOfMonth->copy(); $day->lte($lastDayOfMonth); $day->addDay()) {
             $dates->push($day->copy());
         }

         $caLams = CaLam::withTrashed()
         ->where(function ($query) use ($firstDayOfMonth, $lastDayOfMonth) {
             $query->whereNull('deleted_at') // chưa bị xóa
                 ->orWhereHas('chamCongs', function ($q) use ($firstDayOfMonth, $lastDayOfMonth) {
                     $q->whereBetween('ngay_cham_cong', [
                         $firstDayOfMonth->format('Y-m-d'),
                         $lastDayOfMonth->format('Y-m-d')
                     ]);
                 });
         })
         ->get();

         $nhanViens = NhanVien::where(function ($query) use ($firstDayOfMonth, $lastDayOfMonth) {
            $query->where('trang_thai', 'dang_lam_viec')
                ->orWhereHas('chamCongs', function ($q) use ($firstDayOfMonth, $lastDayOfMonth) {
                    $q->whereBetween('ngay_cham_cong', [
                        $firstDayOfMonth->format('Y-m-d'),
                        $lastDayOfMonth->format('Y-m-d')
                    ]);
                });
        })->paginate(8);

        // Tạo thêm biến chứa toàn bộ nhân viên để dùng riêng cho chấm công nhanh
$nhanViensAll = NhanVien::where('trang_thai', 'dang_lam_viec')->get();


         $chamCongs = DB::table('cham_congs')
             ->join('nhan_viens', 'cham_congs.nhan_vien_id', '=', 'nhan_viens.id')
             ->join('ca_lams', 'cham_congs.ca_lam_id', '=', 'ca_lams.id')
             ->whereBetween('cham_congs.ngay_cham_cong', [
                 $firstDayOfMonth->format('Y-m-d'),
                 $lastDayOfMonth->format('Y-m-d')
             ])
             ->select(
                 'cham_congs.id AS cham_cong_id',
                 'cham_congs.ngay_cham_cong',
                 'cham_congs.nhan_vien_id',
                 'cham_congs.ca_lam_id',
                 'nhan_viens.ho_ten AS ten_nhan_vien',
                 'ca_lams.ten_ca AS ten_ca',
                 DB::raw('CASE WHEN cham_congs.id IS NOT NULL THEN 1 ELSE 0 END AS da_cham_cong')
             )
             ->get();

         if ($request->ajax()) {
             return response()->json([
                //  'dates' => $dates->toArray(),
                 'html' => view('admin.chamcong.listchamcong', compact('dates', 'caLams', 'chamCongs', 'nhanViens'))->render(),
                 'pagination' => (string) $nhanViens->links(), // Trả về phân trang
             ]);
         }

         return view('admin.chamcong.chamcong', compact('dates', 'caLams', 'chamCongs', 'nhanViens','nhanViensAll'));
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
        $changesMade = false; // <-- khai báo ngay đầu tiên

        // ========== CHẤM CÔNG NHANH ==========
        if ($request->filled('ngay_cham_cong_nhanh') && $request->filled('ca_lam_id_nhanh') && $request->filled('nhan_vien_ids_nhanh')) {
            $ngayNhanh = Carbon::parse($request->input('ngay_cham_cong_nhanh'));
            $caNhanhId = $request->input('ca_lam_id_nhanh');
            $nhanViensNhanh = $request->input('nhan_vien_ids_nhanh');
            if (
                $ngayNhanh->gt(Carbon::now()->startOfDay()) ||
                $ngayNhanh->lt(Carbon::now()->startOfDay())
            ) {
                return redirect()->route('cham-cong.index')->with('error', 'Chỉ được chấm công cho ngày hôm nay!');
            }


            foreach ($nhanViensNhanh as $nhanVienId) {
                $chamCong = ChamCong::where('nhan_vien_id', $nhanVienId)
                    ->where('ngay_cham_cong', $ngayNhanh->toDateString())
                    ->where('ca_lam_id', $caNhanhId)
                    ->first();

                if (!$chamCong) {
                    ChamCong::create([
                        'nhan_vien_id' => $nhanVienId,
                        'ngay_cham_cong' => $ngayNhanh->toDateString(),
                        'ca_lam_id' => $caNhanhId,
                    ]);
                    $changesMade = true; // <-- đánh dấu đã thay đổi
                }
            }
        }

        $isEditMode = $request->has('is_edit_mode') && $request->input('is_edit_mode') == 1;
$caLamData = $request->input('ca_lam');

if ($caLamData && is_array($caLamData) && count($caLamData) > 0) {
    $danhSachCaLams = DB::table('ca_lams')->get(); // Lấy tất cả các ca
    $today = Carbon::now()->toDateString();

    foreach ($caLamData as $nhanVienId => $dates) {
        foreach ($dates as $date => $caArray) {
            if ($date != $today) {
                continue;
            }

            foreach ($danhSachCaLams as $caLam) {
                $caKey = Str::slug($caLam->ten_ca, '_'); // Đồng bộ với Blade
                $checked = !empty($caArray[$caKey] ?? null);

                $existingChamCong = ChamCong::where('nhan_vien_id', $nhanVienId)
                    ->where('ngay_cham_cong', $date)
                    ->where('ca_lam_id', $caLam->id)
                    ->first();
                    if ($checked && !$existingChamCong) {
                        ChamCong::create([
                            'nhan_vien_id' => $nhanVienId,
                            'ngay_cham_cong' => $date,
                            'ca_lam_id' => $caLam->id,
                        ]);
                        $changesMade = true;
                    } elseif (!$checked && $existingChamCong && $isEditMode) {
                        $existingChamCong->forceDelete();
                        $changesMade = true;
                    }

            }
        }
    }
}



        // Check biến changesMade chung
        if (!$changesMade) {
            return redirect()->route('cham-cong.index')->with('error', 'Không có dữ liệu nào để lưu!');
        }

        return redirect()->route('cham-cong.index')->with('success', $isEditMode ? 'Cập nhật chấm công thành công!' : 'Chấm công thành công!');
    }













    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $selectedChamCong = ChamCong::where('id', $id)->first();

        if (!$selectedChamCong) {
            return redirect()->route('cham-cong.index')->with('error', 'Không tìm thấy bản ghi chấm công.');
        }

        $selectedChamCongId = $selectedChamCong->id; // Gán ID vào biến mới

        return view('admin.chamcong.bangchamcong', compact('selectedChamCong', 'selectedChamCongId'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nhan_vien_id, $ca_lam_id, $ngay_cham_cong)
    {
        $chamCong = ChamCong::withTrashed()
            ->where('ca_lam_id', $ca_lam_id)
            ->where('ngay_cham_cong', $ngay_cham_cong)
            ->first();

        // Nếu không có dữ liệu, tránh lỗi null
        $moTa = $chamCong->mo_ta ?? '';

        return response()->make("
            <div class='mb-3'>
                <label for='modalGhiChu' class='form-label'>Ghi chú</label>
                <input type='text' class='form-control' id='modalGhiChu' value='" . e($moTa) . "'>
            </div>

        ", 200);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // CẬP NHẬT PHẦN CHẤM CÔNG NHANH
        if ($request->filled('ngay_cham_cong_nhanh') && $request->filled('ca_lam_id_nhanh') && $request->filled('nhan_vien_ids_nhanh')) {
            foreach ($request->nhan_vien_ids_nhanh as $nhanVienId) {
                ChamCong::updateOrCreate(
                    [
                        'ngay_cham_cong' => $request->ngay_cham_cong_nhanh,
                        'nhan_vien_id' => $nhanVienId,
                        'ca_lam_id' => $request->ca_lam_id_nhanh,
                    ],
                    [] // nếu có thêm trường ví dụ 'ghi_chu' hoặc 'nguoi_cap_nhat' thì thêm ở đây
                );
            }
        }

        // CẬP NHẬT PHẦN CHẤM CÔNG LẺ
        if ($request->has('ca_lam')) {
            foreach ($request->ca_lam as $nhanVienId => $ngays) {
                foreach ($ngays as $ngay => $cas) {
                    foreach (['ca_sang' => 1, 'ca_chieu' => 2, 'ca_toi' => 3] as $tenCa => $caId) {
                        if (isset($cas[$tenCa])) {
                            // Nếu được check: tạo hoặc cập nhật
                            ChamCong::updateOrCreate([
                                'ngay_cham_cong' => $ngay,
                                'nhan_vien_id' => $nhanVienId,
                                'ca_lam_id' => $caId
                            ]);
                        } else {
                            // Nếu bỏ check: xoá
                            ChamCong::where([
                                'ngay_cham_cong' => $ngay,
                                'nhan_vien_id' => $nhanVienId,
                                'ca_lam_id' => $caId
                            ])->delete();
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Đã cập nhật chấm công thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function softDelete(Request $request)
    {

        $chamCong = ChamCong::query()
            ->when($request->nhan_vien_id, function ($query, $nhan_vien_id) {
                return $query->where('nhan_vien_id', $nhan_vien_id);
            })
            ->when($request->ca_lam_id, function ($query, $ca_lam_id) {
                return $query->where('ca_lam_id', $ca_lam_id);
            })
            ->when($request->ngay_cham_cong, function ($query, $ngay_cham_cong) {
                return $query->where('ngay_cham_cong', $ngay_cham_cong);
            })
            ->first();


        if ($chamCong) {
            $chamCong->delete(); // Sử dụng xóa mềm
            return redirect()->back()->with('success', 'Chấm công đã được hủy.');
        }


        return redirect()->route('cham-cong.index')->with('success', 'Hủy chấm công thành công!');
    }

    public function restore(Request $request)
    {
        // Lấy đúng bản ghi bị xóa mềm theo điều kiện
        $chamCong = ChamCong::onlyTrashed()
            ->when($request->nhan_vien_id, fn($query, $nhan_vien_id) => $query->where('nhan_vien_id', $nhan_vien_id))
            ->when($request->ca_lam_id, fn($query, $ca_lam_id) => $query->where('ca_lam_id', $ca_lam_id))
            ->when($request->ngay_cham_cong, fn($query, $ngay_cham_cong) => $query->where('ngay_cham_cong', $ngay_cham_cong))
            ->first(); // ✅ Chỉ lấy 1 bản ghi duy nhất

        if ($chamCong) {
            $chamCong->restore(); // ✅ Gọi restore() trên Model
            return redirect()->back()->with('success', 'Chấm công đã được khôi phục.');
        }

        return redirect()->route('cham-cong.index')->with('error', 'Không tìm thấy bản ghi để khôi phục!');
    }



    public function getLichSuChamCong(Request $request)
    {


        $lichSu = ChamCong::withTrashed()->orderBy('created_at', 'desc')->get();
        // Lấy tất cả các cột

        if ($lichSu->isEmpty()) {
            return "<tr><td colspan='2'>Không có lịch sử chấm công</td></tr>";
        }

        $html = "";
        foreach ($lichSu as $item) {
            $thoiGian = Carbon::parse($item->created_at)->format('H:i d/m/Y');
            $html .= "<tr>
                    <td>{$thoiGian}</td>
                    <td>" . ($item->deleted_at ? 'Đã bị hủy' : 'Đã chấm công') . "</td>

                    <td>" . ('Chấm thủ công') . "
                    </td>
                    <td>" . ($item->mo_ta ?? 'Không có mô tả') . "
                    </td>

                 </tr>";
        }

        return $html;
    }
    public function checkChamCong($nhan_vien_id, $ca, $ngay)
    {
        $exists = ChamCong::where([
            ['nhan_vien_id', $nhan_vien_id],
            ['ca_lam_id', $ca],
            ['ngay_cham_cong', $ngay]
        ])->exists();

        return $exists ? "1" : "0"; // Trả về chuỗi "1" hoặc "0"
    }

    public function danhsach(Request $request)
    {
        // Lấy danh sách các ngày có chấm công
        $dates = ChamCong::select(DB::raw('DATE(ngay_cham_cong) as ngay'))
            ->groupBy('ngay')
            ->orderBy('ngay', 'asc')
            ->get();

        if ($dates->isEmpty()) {
            return view('chamcong.danhsach', ['chamCongs' => collect()]); // Trả về danh sách rỗng nếu không có dữ liệu
        }

        $danhSachChamCong = DB::table('ca_lam_nhan_viens')
            ->join('nhan_viens', 'ca_lam_nhan_viens.nhan_vien_id', '=', 'nhan_viens.id')
            ->join('ca_lams', 'ca_lam_nhan_viens.ca_lam_id', '=', 'ca_lams.id')
            ->leftJoin('cham_congs', function ($join) {
                $join->on('ca_lam_nhan_viens.nhan_vien_id', '=', 'cham_congs.nhan_vien_id')
                    ->on('ca_lam_nhan_viens.ca_lam_id', '=', 'cham_congs.ca_lam_id')
                    ->on('ca_lam_nhan_viens.ngay_lam', '=', 'cham_congs.ngay_cham_cong');
            })
            ->whereBetween('ca_lam_nhan_viens.ngay_lam', [
                $dates->first()->ngay,
                $dates->last()->ngay
            ])
            ->select(
                'ca_lam_nhan_viens.id AS ca_lam_nhan_vien_id',
                'ca_lam_nhan_viens.ngay_lam',
                'ca_lam_nhan_viens.ca_lam_id',

                'nhan_viens.id AS nhan_vien_id',
                'nhan_viens.ho_ten AS ten_nhan_vien',

                'ca_lams.id AS ca_lam_id',
                'ca_lams.ten_ca',
                'ca_lams.gio_bat_dau',
                'ca_lams.gio_ket_thuc',

                'cham_congs.id AS cham_cong_id',
                'cham_congs.ngay_cham_cong',
                'cham_congs.gio_vao_lam',
                'cham_congs.gio_ket_thuc',
                'cham_congs.mo_ta',
                'cham_congs.deleted_at'
            )
            ->get();
        return view('admin.chamcong.danhsach', compact('danhSachChamCong'));
    }

    public function export()
    {
        // Xuất file Excel với tên "DanhMucMonAn.xlsx"
        return Excel::download(new ChamCongExport, 'ChamCong.xlsx');
    }

}
