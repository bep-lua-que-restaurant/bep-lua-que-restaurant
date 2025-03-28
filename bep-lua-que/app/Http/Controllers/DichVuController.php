<?php

namespace App\Http\Controllers;

use App\Exports\DichVuExport;
use App\Imports\DichVuImport;
use App\Models\DichVu;
use App\Http\Requests\StoreDichVuRequest;
use Illuminate\Http\Request;

use App\Http\Requests\UpdateDichVuRequest;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class DichVuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DichVu::query();

        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten_dich_vu', 'like', '%' . $request->ten . '%');
        }

        $data = $query->withTrashed()->latest('id')->paginate(15);

        // Xử lý trả về khi yêu cầu là Ajax
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.dichvu.body-list', compact('data'))->render(),
            ]);
        }

        return view('admin.dichvu.list', [
            'data' => $data,
            'route' => route('dich-vu.index'), // URL route cho AJAX
            'tableId' => 'list-container', // ID của bảng'
            'searchInputId' => 'search-name', // ID của ô tìm kiếm
        ]);  
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dichvu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDichVuRequest $request)
    {
        //
        $data = $request->validated();
        DichVu::create($data);

        return redirect()->route('dich-vu.index')->with('success', 'Thêm dịch vụ thành công!');
    }

    

    /**
     * Display the specified resource.
     */
    public function show(DichVu $dichVu)
    {
        return view('admin.dichvu.detail', compact('dichVu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DichVu $dichVu)
    {
        return view('admin.dichvu.edit', compact('dichVu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDichVuRequest $request, DichVu $dichVu)
    {
        $data = $request->validated();

        // Cập nhật dữ liệu
        $dichVu->update($data);

        return back()->with('success', 'Cập nhật dịch vụ thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DichVu $dichVu)
    {
        $dichVu->delete();

        return redirect()->route('dich-vu.index')->with('success', 'Xóa dịch vụ thành công!');
    }

    public function restore($id)
    {
        $dichVu = DichVu::withTrashed()->findOrFail($id);
        $dichVu->restore();

        return redirect()->route('dich-vu.index')->with('success', 'Khôi phục dịch vụ thành công!');
    }

    public function export()
    {
        // Xuất file Excel với tên "DanhMucMonAn.xlsx"
        return Excel::download(new DichVuExport, 'DichVu.xlsx');
    }

    public function importDichVu(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new DichVuImport, $request->file('file'));

        return back()->with('success', 'Nhập dữ liệu thành công!');
    }
}
