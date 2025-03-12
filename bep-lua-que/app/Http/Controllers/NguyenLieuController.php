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
        // Tạo query lấy tất cả nguyên liệu (bao gồm cả bị xóa mềm) và danh mục chưa bị xóa mềm
        $query = NguyenLieu::with(['loaiNguyenLieu'])->withTrashed();

        // Chỉ lấy nguyên liệu có loại nguyên liệu chưa bị xóa mềm
        $query->whereHas('loaiNguyenLieu', function ($q) {
            $q->whereNull('deleted_at'); // Chỉ lấy danh mục chưa bị xóa mềm
        });

        // Lọc theo tên nguyên liệu
        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten_nguyen_lieu', 'like', '%' . $request->ten . '%');
        }

        // Lọc theo trạng thái nguyên liệu (Đang kinh doanh / Ngừng kinh doanh)
        if ($request->has('statusFilter') && $request->statusFilter != '') {
            if ($request->statusFilter == 'Đang kinh doanh') {
                $query->whereNull('deleted_at');
            } elseif ($request->statusFilter == 'Ngừng kinh doanh') {
                $query->whereNotNull('deleted_at');
            }
        }

        // Phân trang và lấy danh sách nguyên liệu
        $data = $query->withTrashed()->latest('id')->paginate(15);

        // Nếu là request AJAX, trả về HTML của danh sách nguyên liệu
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.nguyenlieu.body-list', compact('data'))->render(),
            ]);
        }

        // Trả về view danh sách nguyên liệu
        return view('admin.nguyenlieu.list', [
            'data' => $data,
            'route' => route('nguyen-lieu.index'), // URL route cho AJAX
            'tableId' => 'list-container', // ID của bảng
            'searchInputId' => 'search-name', // ID của ô tìm kiếm
        ]);
    }

    public function getNguyenLieuByLoai($loai_id)
    {
        // $nguyenLieus = NguyenLieu::where('loai_nguyen_lieu_id', $loai_id)->get();
        // return response()->json($nguyenLieus);
    }


    /**
     * Hiển thị form thêm nguyên liệu.
     */
    public function create()
    {
        // return view('admin.nguyenlieu.create');
    }

    /**
     * Lưu nguyên liệu mới vào database.
     */
    public function store(StoreNguyenLieuRequest $request)
    {
        // $data = $request->validated();

        // if ($request->hasFile('hinh_anh')) {
        //     $data['hinh_anh'] = $request->file('hinh_anh')->store('NguyenLieuImg', 'public');
        // }

        // NguyenLieu::create($data);

        // return redirect()->route('nguyen-lieu.index')->with('success', 'Thêm nguyên liệu thành công!');
    }

    /**
     * Hiển thị thông tin chi tiết nguyên liệu.
     */

    public function show($id)
    {
        try {
            // Tìm nguyên liệu bao gồm cả loại nguyên liệu, kể cả khi bị xóa mềm
            $nguyenLieu = NguyenLieu::with(['loaiNguyenLieu'])->withTrashed()->findOrFail($id);

            return view('admin.nguyenlieu.detail', compact('nguyenLieu'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Nguyên liệu không tồn tại hoặc đã bị xóa.');
        }
    }



    /**
     * Hiển thị form sửa nguyên liệu.
     */
    public function edit(NguyenLieu $nguyenLieu)
    {
        // return view('admin.nguyenlieu.edit', compact('nguyenLieu'));
    }

    /**
     * Cập nhật thông tin nguyên liệu.
     */
    public function update(UpdateNguyenLieuRequest $request, NguyenLieu $nguyenLieu)
    {
        // $data = $request->validated();

        // if ($request->hasFile('hinh_anh')) {
        //     if ($nguyenLieu->hinh_anh) {
        //         Storage::disk('public')->delete($nguyenLieu->hinh_anh);
        //     }
        //     $data['hinh_anh'] = $request->file('hinh_anh')->store('NguyenLieuImg', 'public');
        // }

        // $nguyenLieu->update($data);

        // return back()->with('success', 'Cập nhật nguyên liệu thành công!');
    }

    /**
     * Xóa nguyên liệu (soft delete).
     */
    public function destroy(NguyenLieu $nguyenLieu)
    {
        // $nguyenLieu->delete();

        // return redirect()->route('nguyen-lieu.index')->with('success', 'Xóa nguyên liệu thành công!');
    }

    /**
     * Khôi phục nguyên liệu đã xóa.
     */
    public function restore($id)
    {
        //     $nguyenLieu = NguyenLieu::withTrashed()->findOrFail($id);
        //     $nguyenLieu->restore();

        //     return redirect()->route('nguyen-lieu.index')->with('success', 'Khôi phục nguyên liệu thành công!');
    }
}
