<?php

namespace App\Http\Controllers;

use App\Models\LoaiNguyenLieu;
use App\Http\Requests\StoreLoaiNguyenLieuRequest;
use App\Http\Requests\UpdateLoaiNguyenLieuRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LoaiNguyenLieuController extends Controller
{
    /**
     * Hiển thị danh sách loại nguyên liệu.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = LoaiNguyenLieu::query()->withTrashed();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('trang_thai', function ($row) {
                    return $row->deleted_at
                        ? '<div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Đã xóa</div>'
                        : '<div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Đang sử dụng</div>';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex align-items-center">';
                    $html .= '<a href="' . route('loai-nguyen-lieu.edit', $row->id) . '" class="btn btn-warning btn-sm p-2 m-2"><i class="fa fa-edit"></i></a>';

                    if ($row->deleted_at) {
                        $html .= '<form action="' . route('loai-nguyen-lieu.restore', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field() .
                            '<button type="submit" class="btn btn-success btn-sm p-2 m-2" title="Khôi phục"><i class="fa fa-recycle"></i></button>'
                            . '</form>';
                    } else {
                        $html .= '<form action="' . route('loai-nguyen-lieu.destroy', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field() . method_field('DELETE') .
                            '<button type="submit" class="btn btn-danger btn-sm p-2 m-2" title="Xóa"><i class="fa fa-trash"></i></button>'
                            . '</form>';
                    }

                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['trang_thai', 'action'])
                ->make(true);
        }

        return view('admin.loainguyenlieu.list', [
            'route' => route('loai-nguyen-lieu.index'),
            'tableId' => 'list-container',
        ]);
    }

    /**
     * Hiển thị form thêm mới.
     */
    public function create()
    {
        return view('admin.loainguyenlieu.create');
    }

    /**
     * Lưu loại nguyên liệu mới.
     */
    public function store(StoreLoaiNguyenLieuRequest $request)
    {
        LoaiNguyenLieu::create($request->validated());

        return redirect()->route('loai-nguyen-lieu.index')->with('success', 'Thêm loại nguyên liệu thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa.
     */
    public function edit(LoaiNguyenLieu $loaiNguyenLieu)
    {
        return view('admin.loainguyenlieu.edit', compact('loaiNguyenLieu'));
    }

    /**
     * Cập nhật loại nguyên liệu.
     */
    public function update(UpdateLoaiNguyenLieuRequest $request, LoaiNguyenLieu $loaiNguyenLieu)
    {
        $loaiNguyenLieu->update($request->validated());

        return back()->with('success', 'Cập nhật thành công!');
    }

    /**
     * Xóa mềm.
     */
    public function destroy(LoaiNguyenLieu $loaiNguyenLieu)
    {
        $soLuongNguyenLieu = $loaiNguyenLieu->nguyenLieus()->count();

        if ($soLuongNguyenLieu > 0) {
            // Nếu là request AJAX thì trả JSON lỗi
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không thể xóa! Đang có ' . $soLuongNguyenLieu . ' nguyên liệu thuộc loại này.'
                ], 400);
            }

            // Nếu không phải ajax (ví dụ submit form truyền thống)
            return redirect()->route('loai-nguyen-lieu.index')
                ->with('error', 'Không thể xóa! Đang có nguyên liệu thuộc loại này.');
        }

        $loaiNguyenLieu->delete();

        if (request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa loại nguyên liệu!'
            ]);
        }

        return redirect()->route('loai-nguyen-lieu.index')
            ->with('success', 'Đã xóa loại nguyên liệu!');
    }



    /**
     * Khôi phục đã xóa.
     */
    public function restore($id)
    {
        try {
            $loaiNguyenLieu = LoaiNguyenLieu::withTrashed()->findOrFail($id);
            $loaiNguyenLieu->restore();

            return redirect()->route('loai-nguyen-lieu.index')->with('success', 'Đã khôi phục loại nguyên liệu!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Không thể khôi phục loại nguyên liệu.');
        }
    }
}
