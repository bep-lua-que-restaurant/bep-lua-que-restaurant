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
        $query = ChucVu::query();

        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten_chuc_vu', 'like', '%' . $request->ten . '%');
        }

        $data = $query->withTrashed()->latest('id')->paginate(15);

        // Xử lý trả về khi yêu cầu là Ajax
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.chucvu.body-list', compact('data'))->render(),
            ]);
        }

        return view('admin.chucvu.list', [
            'data' => $data,
            'route' => route('chuc-vu.index'), // URL route cho AJAX
            'tableId' => 'list-container', // ID của bảng'
            'searchInputId' => 'search-name', // ID của ô tìm kiếm
        ]);  
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
    public function show(ChucVu $dichVu)
    {
        return view('admin.chucvu.detail', compact('chucvu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChucVu $chucVu)
    {
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
