<?php

namespace App\Http\Controllers;

use App\Models\DanhMucMonAn;
use App\Http\Requests\StoreDanhMucMonAnRequest;
use App\Http\Requests\UpdateDanhMucMonAnRequest;
use Illuminate\Http\Request;
use App\Exports\DanhMucMonAnExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DanhMucMonAnImport;
use Illuminate\Support\Facades\Storage;

class DanhMucMonAnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request  $request)
    {
        $query = DanhMucMonAn::query();

        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten', 'like', '%' . $request->ten . '%');
        }

        if ($request->has('statusFilter') && $request->statusFilter != '') {
            if ($request->statusFilter == 'Đang kinh doanh') {
                $query->whereNull('deleted_at');
            } elseif ($request->statusFilter == 'Ngừng kinh doanh') {
                $query->whereNotNull('deleted_at');
            }
        }

        $data = $query->withTrashed()->latest('id')->paginate(15);

        // Xử lý trả về khi yêu cầu là Ajax
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.danhmuc.body-list', compact('data'))->render(),
            ]);
        }

        return view('admin.danhmuc.list', [
            'data' => $data,
            'route' => route('danh-muc-mon-an.index'), // URL route cho AJAX
            'tableId' => 'list-container', // ID của bảng
            'searchInputId' => 'search-name', // ID của ô tìm kiếm
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.danhmuc.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDanhMucMonAnRequest $request)
    {
        //
        $data = $request->validated();

        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('DanhMucImg', 'public');
        }

        DanhMucMonAn::create($data);

        return redirect()->route('danh-muc-mon-an.index')->with('success', 'Thêm danh mục thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DanhMucMonAn $danhMucMonAn)
    {
        return view('admin.danhmuc.detail', compact('danhMucMonAn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DanhMucMonAn $danhMucMonAn)
    {
        return view('admin.danhmuc.edit', compact('danhMucMonAn'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDanhMucMonAnRequest $request, DanhMucMonAn $danhMucMonAn)
    {
        $data = $request->validated();

        // Kiểm tra nếu có file ảnh mới
        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($danhMucMonAn->hinh_anh) {
                Storage::disk('public')->delete($danhMucMonAn->hinh_anh);
            }

            // Lưu ảnh mới
            $data['hinh_anh'] = $request->file('hinh_anh')->store('DanhMucImg', 'public');
        }

        // Cập nhật dữ liệu
        $danhMucMonAn->update($data);

        return back()->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DanhMucMonAn $danhMucMonAn)
    {
        $danhMucMonAn->delete();

        return redirect()->route('danh-muc-mon-an.index')->with('success', 'Xóa danh mục thành công!');
    }

    public function restore($id)
    {
        $danhMucMonAn = DanhMucMonAn::withTrashed()->findOrFail($id);
        $danhMucMonAn->restore();

        return redirect()->route('danh-muc-mon-an.index')->with('success', 'Khôi phục danh mục thành công!');
    }

    public function export()
    {
        // Xuất file Excel với tên "DanhMucMonAn.xlsx"
        return Excel::download(new DanhMucMonAnExport, 'DanhMucMonAn.xlsx');
    }

    public function importDanhMucMonAn(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new DanhMucMonAnImport, $request->file('file'));

        return back()->with('success', 'Nhập dữ liệu thành công!');
    }
}
