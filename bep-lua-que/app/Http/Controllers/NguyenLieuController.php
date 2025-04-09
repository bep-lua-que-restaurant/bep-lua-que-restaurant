<?php

namespace App\Http\Controllers;

use App\Models\NguyenLieu;
use App\Models\LoaiNguyenLieu;
use App\Http\Requests\StoreNguyenLieuRequest;
use App\Http\Requests\UpdateNguyenLieuRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class NguyenLieuController extends Controller
{
    /**
     * Hiển thị danh sách nguyên liệu.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = NguyenLieu::query()
                ->with(['loaiNguyenLieu']) // Giả sử có quan hệ loaiNguyenLieu()
                ->withTrashed();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('trang_thai', function ($row) {
                    return $row->deleted_at
                        ? '<div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Đã xóa</div>'
                        : '<div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Đang sử dụng</div>';
                })
                ->addColumn('loai_nguyen_lieu', function ($row) {
                    return $row->loaiNguyenLieu ? $row->loaiNguyenLieu->ten_loai : 'Không xác định';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex align-items-center">';

                    // Nút xem chi tiết
                    $html .= '<a href="' . route('nguyen-lieu.show', $row->id) . '" class="btn btn-info btn-sm p-2 m-2" title="Xem chi tiết"><i class="fa fa-eye"></i></a>';

                    // Nút chỉnh sửa
                    // $html .= '<a href="' . route('nguyen-lieu.edit', $row->id) . '" class="btn btn-warning btn-sm p-2 m-2" title="Chỉnh sửa"><i class="fa fa-edit"></i></a>';

                    // Nút xóa hoặc khôi phục tùy theo trạng thái soft delete
                    if ($row->deleted_at) {
                        $html .= '<form action="' . route('nguyen-lieu.restore', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field()
                            . '<button type="submit" class="btn btn-success btn-sm p-2 m-2" title="Khôi phục"><i class="fa fa-recycle"></i></button>'
                            . '</form>';
                    } else {
                        $html .= '<form action="' . route('nguyen-lieu.destroy', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field()
                            . method_field('DELETE')
                            . '<button type="submit" class="btn btn-danger btn-sm p-2 m-2" title="Xóa"><i class="fa fa-trash"></i></button>'
                            . '</form>';
                    }

                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['trang_thai', 'action'])
                ->make(true);
        }

        return view('admin.nguyenlieu.list', [
            'route' => route('nguyen-lieu.index'),
            'tableId' => 'list-container',
            'searchInputId' => 'searchInput',
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
            $nguyenLieu = NguyenLieu::withTrashed()
                ->with(['loaiNguyenLieu' => function ($query) {
                    $query->withTrashed();
                }])
                ->findOrFail($id);

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
        try {
            $nguyenLieu->delete();
            return redirect()->route('nguyen-lieu.index')->with('success', 'Xóa nguyên liệu thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xóa nguyên liệu.');
        }
    }


    /**
     * Khôi phục nguyên liệu đã xóa.
     */
    public function restore($id)
    {
        try {
            $nguyenLieu = NguyenLieu::withTrashed()->findOrFail($id);
            if ($nguyenLieu->trashed()) {
                $nguyenLieu->restore();
                return redirect()->route('nguyen-lieu.index')->with('success', 'Khôi phục nguyên liệu thành công!');
            } else {
                return redirect()->back()->with('info', 'Nguyên liệu này đang hoạt động và không cần khôi phục.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Không thể khôi phục nguyên liệu.');
        }
    }
}
