<?php

namespace App\Http\Controllers;

use App\Exports\MaGiamGiaExport;
use App\Models\MaGiamGia;
use App\Http\Requests\StoreMaGiamGiaRequest;
use App\Http\Requests\UpdateMaGiamGiaRequest;
use App\Imports\MaGiamGiaImport;
use Illuminate\Http\Request;
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
            $query->whereNull('deleted_at');
        } else if ($statusFilter === 'Đã ngừng hoạt động') {
            $query->whereNotNull('deleted_at');
        }
    }

    $data = $query->withTrashed()->paginate(10);

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
        $maGiamGia = MaGiamGia::findOrFail($id);
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
        $maGiamGia->delete();

        return redirect()->route('ma-giam-gia.index')
            ->with('success', 'Mã giảm giá đã được xóa.');
    }

    public function restore($id)
    {
        $maGiamGia = MaGiamGia::withTrashed()->findOrFail($id);
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
