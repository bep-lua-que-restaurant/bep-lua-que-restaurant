<?php

namespace App\Http\Controllers;

use App\Exports\ComBoExport;
use App\Imports\ComboImport;
use App\Models\ComBo;
use App\Http\Requests\StoreComBoRequest;
use App\Http\Requests\UpdateComBoRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ComBoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request  $request)
    {
        $query = ComBo::query();

        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten', 'like', '%' . $request->ten . '%');
        }

        $data = $query->withTrashed()->latest('id')->paginate(15);

        // Xử lý trả về khi yêu cầu là Ajax
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.combo.body-list', compact('data'))->render(),
            ]);
        }

        return view('admin.combo.list', [
            'data' => $data,
            'route' => route('com-bo.index'), // URL route cho AJAX
            'tableId' => 'list-container', // ID của bảng
            'searchInputId' => 'search-name', // ID của ô tìm kiếm
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.combo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreComBoRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('ComBoImg', 'public');
        }

        ComBo::create($data);

        return redirect()->route('com-bo.index')->with('success', 'Thêm combo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ComBo $comBo)
    {
        return view('admin.combo.detail', compact('comBo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ComBo $comBo)
    {
        return view('admin.combo.edit', compact('comBo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateComBoRequest $request, ComBo $comBo)
    {
        $data = $request->validated();

        // Kiểm tra nếu có file ảnh mới
        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($comBo->hinh_anh) {
                Storage::disk('public')->delete($comBo->hinh_anh);
            }

            // Lưu ảnh mới
            $data['hinh_anh'] = $request->file('hinh_anh')->store('ComBoImg', 'public');
        }

        // Cập nhật dữ liệu
        $comBo->update($data);

        return back()->with('success', 'Cập nhật combo thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ComBo $comBo)
    {
        $comBo->delete();

        return back()->with('success', 'Xóa combo thành công!');
    }

    public function restore($id)
    {
        $comBo = ComBo::withTrashed()->findOrFail($id);
        $comBo->restore();

        return redirect()->route('com-bo.index')->with('success', 'Khôi phục combo thành công!');
    }
    public function export()
    {
        // Xuất file Excel với tên "Combo.xlsx"
        return Excel::download(new ComBoExport, 'ComBo.xlsx');
    }

    public function importComBo(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new ComboImport, $request->file('file'));

        return back()->with('success', 'Nhập dữ liệu thành công!');
    }

}
