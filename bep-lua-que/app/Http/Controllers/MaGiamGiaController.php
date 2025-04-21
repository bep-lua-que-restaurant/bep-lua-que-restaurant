<?php

namespace App\Http\Controllers;

use App\Exports\MaGiamGiaExport;
use App\Models\MaGiamGia;
use App\Http\Requests\StoreMaGiamGiaRequest;
use App\Http\Requests\UpdateMaGiamGiaRequest;
use App\Imports\MaGiamGiaImport;
use App\Models\HoaDon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class MaGiamGiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request)
     {
         // TỰ ĐỘNG UPDATE: Những mã hết hạn hoặc hết lượt dùng thì set deleted_at = now()
         MaGiamGia::whereNull('deleted_at')
             ->where(function($query) {
                 $query->whereDate('end_date', '<', Carbon::today())
                       ->orWhere(function($q) {
                           // Kiểm tra nếu số lượt đã dùng >= usage_limit
                           $q->where('usage_limit', '<=', 0)
                             ->orWhere('usage_limit', '<=', DB::raw('(SELECT COUNT(*) FROM hoa_dons WHERE hoa_dons.id_ma_giam = ma_giam_gias.id)')); // Đếm số lượt sử dụng từ hoa_dons
                       });
             })
             ->update(['deleted_at' => now()]);
         
         $searchInput = $request->input('searchInput');
         $statusFilter = $request->input('statusFilter');
     
         $query = MaGiamGia::query();
     
         // Apply search filter
         if ($searchInput) {
             $query->where('code', 'like', '%' . $searchInput . '%');
         }
     
         // Apply status filter
         if ($statusFilter && $statusFilter !== 'Tất cả') {
             if ($statusFilter === 'Đang hoạt động') {
                 $query->where(function($q) {
                     $q->whereNull('deleted_at')
                       ->whereDate('end_date', '>=', Carbon::today())
                       ->where('usage_limit', '>', 0)
                       ->whereRaw('(SELECT COUNT(*) FROM hoa_dons WHERE hoa_dons.id_ma_giam = ma_giam_gias.id) < usage_limit'); // Đảm bảo số lượt chưa dùng hết
                 });
             } else if ($statusFilter === 'Đã ngừng hoạt động') {
                 $query->where(function($q) {
                     $q->whereNotNull('deleted_at')
                       ->orWhereDate('end_date', '<', Carbon::today())
                       ->orWhere('usage_limit', '<=', 0)
                       ->orWhereRaw('(SELECT COUNT(*) FROM hoa_dons WHERE hoa_dons.id_ma_giam = ma_giam_gias.id) >= usage_limit'); // Đã hết lượt hoặc hết hạn
                 });
             }
         }
     
         $data = $query->withTrashed()->paginate(10);
     // Thêm số lượt đã sử dụng vào dữ liệu
        foreach ($data as $item) {
        $item->usage_count = HoaDon::where('id_ma_giam', $item->id)->count();
        }
         return view('admin.magiamgia.list', compact('data'));
     }
     

    


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.magiamgia.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaGiamGiaRequest $request)
    {
        $validated = $request->validated();
    
        // Nếu không có usage_limit, gán mặc định là 0
        $validated['usage_limit'] = $validated['usage_limit'] ?? 0;
    
        MaGiamGia::create($validated);
    
        return redirect()->route('ma-giam-gia.index')->with('success', 'Thêm mã giảm giá thành công!');
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $maGiamGia = MaGiamGia::withTrashed()->findOrFail($id); // Bao gồm cả bản ghi đã bị xóa mềm
    return view('admin.magiamgia.show', compact('maGiamGia'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)

    {
        $maGiamGia = MaGiamGia::withTrashed()->findOrFail($id);
        return view('admin.magiamgia.edit', compact('maGiamGia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $maGiamGia = MaGiamGia::findOrFail($id);
    
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('ma_giam_gias', 'code')->ignore($maGiamGia->id),
            ],
            'type'            => 'required|in:percentage,fixed',
            'value'           => 'required|numeric|min:0.01',
            'min_order_value' => 'required|numeric|min:0',  // Đổi từ nullable thành required
            'start_date'      => 'required|date|after_or_equal:today',
            'end_date'        => 'required|date|after:start_date',
            'usage_limit'     => 'required|integer|min:0',  // Đổi từ nullable thành required
        ], [
            'code.required' => 'Mã giảm giá không được để trống.',
            'code.string' => 'Mã giảm giá phải là chuỗi.',
            'code.max' => 'Mã giảm giá tối đa 20 ký tự.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
    
            'type.required' => 'Vui lòng chọn loại giảm giá.',
            'type.in' => 'Loại giảm giá không hợp lệ.',
    
            'value.required' => 'Giá trị giảm không được để trống.',
            'value.numeric' => 'Giá trị giảm phải là số.',
            'value.min' => 'Giá trị giảm phải lớn hơn 0.',
    
            'min_order_value.required' => 'Đơn hàng tối thiểu không được để trống.',
            'min_order_value.numeric' => 'Đơn hàng tối thiểu phải là số.',
            'min_order_value.min' => 'Đơn hàng tối thiểu phải lớn hơn hoặc bằng 0.',
    
            'start_date.required' => 'Ngày bắt đầu không được để trống.',
            'start_date.date' => 'Ngày bắt đầu không đúng định dạng.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',
    
            'end_date.required' => 'Ngày kết thúc không được để trống.',
            'end_date.date' => 'Ngày kết thúc không đúng định dạng.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
    
            'usage_limit.required' => 'Giới hạn sử dụng không được để trống.',
            'usage_limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Giới hạn sử dụng không được âm.',
        ]);
    
        // Nếu không có usage_limit, gán mặc định là 0
        // Trong trường hợp này không còn nullable, nên cần đảm bảo đã nhập usage_limit
        $validated['usage_limit'] = $validated['usage_limit'] ?? 0;
    
        $maGiamGia->update($validated);
    
        return back()->with('success', 'Cập nhật mã giảm giá thành công!');
    }
    

    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    $maGiamGia = MaGiamGia::findOrFail($id);

    // Kiểm tra xem mã giảm giá có được sử dụng trong hóa đơn chưa thanh toán không
    $isUsed = HoaDon::where('id_ma_giam', $id)
    ->whereHas('hoaDonBans', function ($query) {
        $query->where('hoa_don_bans.trang_thai', '!=', 'da_thanh_toan');
    })
    ->exists();

    if ($isUsed) {
    return redirect()->route('ma-giam-gia.index')
        ->with('error', 'Không thể xóa mã giảm giá vì đang được sử dụng trong hóa đơn chưa thanh toán.');
    }


    $maGiamGia->delete();

    return redirect()->route('ma-giam-gia.index')
        ->with('success', 'Mã giảm giá đã được xóa.');
    }

    public function restore($id)
{
    $maGiamGia = MaGiamGia::withTrashed()->findOrFail($id);
    
    // Nếu mã đã hoàn toàn hết hạn (qua hết ngày) hoặc hết số lượt
    if (Carbon::parse($maGiamGia->end_date)->endOfDay()->lt(now())) {
        return redirect()->route('ma-giam-gia.index')->with('error', 'Mã giảm giá đã hết hạn, không thể khôi phục.');
    }

    // Kiểm tra nếu mã giảm giá đã hết số lượt sử dụng (usage_limit <= 0)
    $usageCount = HoaDon::where('id_ma_giam', $maGiamGia->id)->count(); // Đếm số lần đã sử dụng mã giảm giá
    if ($maGiamGia->usage_limit <= $usageCount) {
        return redirect()->route('ma-giam-gia.index')->with('error', 'Mã giảm giá đã hết số lượt sử dụng, không thể khôi phục.');
    }

    // Nếu không hết hạn và còn lượt, cho phép khôi phục
    $maGiamGia->restore();
    
    return redirect()->route('ma-giam-gia.index')->with('success', 'Khôi phục thành công!');
}

    

    public function export()
    {
        // Xuất file Excel với tên "DanhMucMonAn.xlsx"
        return Excel::download(new MaGiamGiaExport, 'MaGiamGia.xlsx');
    }

    public function importMaGiamGia(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new MaGiamGiaImport, $request->file('file'));

        return back()->with('success', 'Nhập dữ liệu thành công!');
    }
}
