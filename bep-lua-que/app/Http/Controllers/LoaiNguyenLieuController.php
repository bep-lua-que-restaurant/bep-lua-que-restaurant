<?php

namespace App\Http\Controllers;

use App\Exports\LoaiNguyenLieuExport;
use App\Imports\LoaiNguyenLieuImport;
use App\Models\LoaiNguyenLieu;
use App\Http\Requests\StoreLoaiNguyenLieuRequest;
use App\Http\Requests\UpdateLoaiNguyenLieuRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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

                    // Nút sửa
                    $html .= '<button type="button" class="btn btn-warning btn-sm p-2 m-2 btn-edit" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa fa-edit"></i></button>';

                    // Nút khôi phục hoặc xóa
                    if ($row->deleted_at) {
                        $html .= '<form action="' . route('loai-nguyen-lieu.restore', $row->id) . '" method="POST" class="form-delete-restore" style="display:inline;">'
                            . csrf_field() .
                            '<button type="submit" class="btn btn-success btn-sm p-2 m-2" title="Khôi phục"><i class="fa fa-recycle"></i></button>'
                            . '</form>';
                    } else {
                        $html .= '<form action="' . route('loai-nguyen-lieu.destroy', $row->id) . '" method="POST" class="form-delete-restore" style="display:inline;">'
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
     * Lưu loại nguyên liệu mới.
     */
    public function store(StoreLoaiNguyenLieuRequest $request)
    {
        $data = $request->validated();

        $loai = LoaiNguyenLieu::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Tạo loại nguyên liệu thành công!',
            'data' => $loai
        ]);
    }

    /**
     * Hiển thị thông tin một loại nguyên liệu (cho edit ajax).
     */
    public function show(LoaiNguyenLieu $loaiNguyenLieu)
    {
        return response()->json($loaiNguyenLieu);
    }

    /**
     * Cập nhật loại nguyên liệu.
     */
    public function update(UpdateLoaiNguyenLieuRequest $request, LoaiNguyenLieu $loaiNguyenLieu)
    {
        $data = $request->validated();

        $loaiNguyenLieu->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật loại nguyên liệu thành công!',
            'data' => $loaiNguyenLieu
        ]);
    }

    /**
     * Xóa mềm loại nguyên liệu.
     */
    public function destroy(LoaiNguyenLieu $loaiNguyenLieu)
    {
        $soLuongNguyenLieu = $loaiNguyenLieu->nguyenLieus()->count();

        if ($soLuongNguyenLieu > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể xóa! Đang có ' . $soLuongNguyenLieu . ' nguyên liệu thuộc loại này.'
            ], 400);
        }

        $loaiNguyenLieu->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa loại nguyên liệu!'
        ]);
    }

    /**
     * Khôi phục loại nguyên liệu đã xóa.
     */
    public function restore($id)
    {
        $loai = LoaiNguyenLieu::withTrashed()->findOrFail($id);
        $loai->restore();

        return redirect()->route('loai-nguyen-lieu.index')->with('success', 'Đã khôi phục loại nguyên liệu!');
    }
    public function export()
    {
        return Excel::download(new LoaiNguyenLieuExport, 'loai_nguyen_lieu.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new LoaiNguyenLieuImport, $request->file('file'));

        return back()->with('success', 'Import thành công!');
    }
}
