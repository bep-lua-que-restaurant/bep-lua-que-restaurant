<?php

namespace App\Http\Controllers;

use App\Models\NguyenLieu;
use App\Http\Requests\StoreNguyenLieuRequest;
use App\Http\Requests\UpdateNguyenLieuRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NguyenLieuController extends Controller
{
    /**
     * Hiển thị danh sách nguyên liệu.
     */
    public function index(Request $request)
    {
        $query = NguyenLieu::query();

        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten_nguyen_lieu', 'like', '%' . $request->ten . '%');
        }

        $data = $query->withTrashed()->latest('id')->paginate(15);

        return view('admin.nguyenlieu.list', compact('data'));
    }
    public function getNguyenLieuByLoai($loai_id)
    {
        $nguyenLieus = NguyenLieu::where('loai_nguyen_lieu_id', $loai_id)->get();
        return response()->json($nguyenLieus);
    }


    /**
     * Hiển thị form thêm nguyên liệu.
     */
    public function create()
    {
        return view('admin.nguyenlieu.create');
    }

    /**
     * Lưu nguyên liệu mới vào database.
     */
    public function store(StoreNguyenLieuRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('NguyenLieuImg', 'public');
        }

        NguyenLieu::create($data);

        return redirect()->route('nguyen-lieu.index')->with('success', 'Thêm nguyên liệu thành công!');
    }

    /**
     * Hiển thị thông tin chi tiết nguyên liệu.
     */
    public function show(NguyenLieu $nguyenLieu)
    {
        return view('admin.nguyenlieu.detail', compact('nguyenLieu'));
    }

    /**
     * Hiển thị form sửa nguyên liệu.
     */
    public function edit(NguyenLieu $nguyenLieu)
    {
        return view('admin.nguyenlieu.edit', compact('nguyenLieu'));
    }

    /**
     * Cập nhật thông tin nguyên liệu.
     */
    public function update(UpdateNguyenLieuRequest $request, NguyenLieu $nguyenLieu)
    {
        $data = $request->validated();

        if ($request->hasFile('hinh_anh')) {
            if ($nguyenLieu->hinh_anh) {
                Storage::disk('public')->delete($nguyenLieu->hinh_anh);
            }
            $data['hinh_anh'] = $request->file('hinh_anh')->store('NguyenLieuImg', 'public');
        }

        $nguyenLieu->update($data);

        return back()->with('success', 'Cập nhật nguyên liệu thành công!');
    }

    /**
     * Xóa nguyên liệu (soft delete).
     */
    public function destroy(NguyenLieu $nguyenLieu)
    {
        $nguyenLieu->delete();

        return redirect()->route('nguyen-lieu.index')->with('success', 'Xóa nguyên liệu thành công!');
    }

    /**
     * Khôi phục nguyên liệu đã xóa.
     */
    public function restore($id)
    {
        $nguyenLieu = NguyenLieu::withTrashed()->findOrFail($id);
        $nguyenLieu->restore();

        return redirect()->route('nguyen-lieu.index')->with('success', 'Khôi phục nguyên liệu thành công!');
    }
}
