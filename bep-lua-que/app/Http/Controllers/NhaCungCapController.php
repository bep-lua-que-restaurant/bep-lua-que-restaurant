<?php

namespace App\Http\Controllers;

use App\Exports\DanhMucMonAnExport;
use App\Exports\NhaCungCapExport;
use App\Models\DanhMucMonAn;
use App\Models\NhaCungCap;
use App\Http\Requests\StoreNhaCungCapRequest;
use App\Http\Requests\UpdateNhaCungCapRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class NhaCungCapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request  $request)
    {
        $query = NhaCungCap::query();

        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten', 'like', '%' . $request->ten . '%');
        }

        $data = $query->withTrashed()->latest('id')->paginate(15);

        // Xử lý trả về khi yêu cầu là Ajax
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.nhacungcap.body-list', compact('data'))->render(),
            ]);
        }

        return view('admin.nhacungcap.list', [
            'data' => $data,
            'route' => route('nha-cung-cap.index'), // URL route cho AJAX
            'tableId' => 'list-container', // ID của bảng
            'searchInputId' => 'search-name', // ID của ô tìm kiếm
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.nhacungcap.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNhaCungCapRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('hinhAnh')) {
            $data['hinhAnh'] = $request->file('hinhAnh')->store('NhaCungCapImg', 'public');
        }

        NhaCungCap::create($data);

        return redirect()->route('nha-cung-cap.index')->with('success', 'Thêm nhà cung cấp thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(NhaCungCap $nhaCungCap)
    {
        return view('admin.nhacungcap.detail', compact('nhaCungCap'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NhaCungCap $nhaCungCap)
    {
        return view('admin.nhacungcap.edit', compact('nhaCungCap'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNhaCungCapRequest $request, NhaCungCap $nhaCungCap)
    {
        $data = $request->validated();

        // Kiểm tra nếu có file ảnh mới
        if ($request->hasFile('hinhAnh')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($nhaCungCap->hinhAnh) {
                Storage::disk('public')->delete($nhaCungCap->hinhAnh);
            }

            // Lưu ảnh mới
            $data['hinhAnh'] = $request->file('hinhAnh')->store('NhaCungCapImg', 'public');
        }

        // Cập nhật dữ liệu
        $nhaCungCap->update($data);

        return back()->with('success', 'Cập nhật nhà cung cấp thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NhaCungCap $nhaCungCap)
    {
        $nhaCungCap->delete();

        return redirect()->route('nha-cung-cap.index')->with('success', 'Xóa nhà cung cấp thành công!');
    }

    public function restore($id)
    {
        $nhaCungCap = NhaCungCap::withTrashed()->findOrFail($id);
        $nhaCungCap->restore();

        return redirect()->route('nha-cung-cap.index')->with('success', 'Khôi phục nhà cung cấp thành công!');
    }

    public function export()
    {
        // Xuất file Excel với tên "NhaCungCap.xlsx"
        return Excel::download(new NhaCungCapExport, 'NhaCungCap.xlsx');
    }
}
