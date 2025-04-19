<?php

namespace App\Http\Controllers;

use App\Exports\ChucVuExport;
use App\Imports\ChucVuImport;
use App\Models\ChucVu;
use App\Http\Requests\StoreChucVuRequest;
use App\Http\Requests\UpdateChucVuRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ChucVuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchInput = $request->input('searchInput');
        $statusFilter = $request->input('statusFilter');
    
        $query = ChucVu::query();
    
        // Apply search filter
        if ($searchInput) {
            $query->where('ten_chuc_vu', 'like', '%' . $searchInput . '%');
        }
    
        // Apply status filter
        if ($statusFilter && $statusFilter !== 'Tất cả') {
            if ($statusFilter === 'Đang hoạt động') {
                $query->whereNull('deleted_at');
            } else if ($statusFilter === 'Đã ngừng hoạt động') {
                $query->whereNotNull('deleted_at');
            }
        }
        $data = $query->withTrashed()->latest('id')->paginate(10);
        return view('admin.chucvu.list', compact('data')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.chucvu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChucVuRequest $request)
    {
        //
        $data = $request->validated();
        ChucVu::create($data);

        return redirect()->route('chuc-vu.index')->with('success', 'Thêm chức vụ thành công!');
    }

    

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $chucVu = ChucVu::withTrashed()->findOrFail($id);
    return view('admin.chucvu.detail', compact('chucVu'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $chucVu = ChucVu::withTrashed()->findOrFail($id);
    return view('admin.chucvu.edit', compact('chucVu'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChucVuRequest $request, ChucVu $chucVu)
    {
        $data = $request->validated();

        // Cập nhật dữ liệu
        $chucVu->update($data);

        return back()->with('success', 'Cập nhật dịch vụ thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChucVu $chucVu)
    {
        $chucVu->delete();

        return redirect()->route('chuc-vu.index')->with('success', 'Xóa chức vụ thành công!');
    }

    public function restore($id)
    {
        $chucVu = ChucVu::withTrashed()->findOrFail($id);
        $chucVu->restore();

        return redirect()->route('chuc-vu.index')->with('success', 'Khôi phục chúc vụ thành công!');
    }

    public function export()
    {
        // Xuất file Excel với tên "DanhMucMonAn.xlsx"
        return Excel::download(new ChucVuExport, 'ChucVu.xlsx');
    }

    public function importChucVu(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new ChucVuImport, $request->file('file'));

        return back()->with('success', 'Nhập dữ liệu thành công!');
    }
}
