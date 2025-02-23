<?php

namespace App\Http\Controllers;

use App\Exports\LoaiNguyenLieuExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoaiNguyenLieuRequest;
use App\Http\Requests\UpdateLoaiNguyenLieuRequest;
use App\Models\LoaiNguyenLieu;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LoaiNguyenLieuController extends Controller
{
    public function index(Request $request)
    {
        $query = LoaiNguyenLieu::query();

        if ($request->has('ten_loai') && $request->ten != '') {
            $query->where('ten_loai', 'like', '%' . $request->ten . '%');
        }

        if ($request->has('statusFilter') && $request->statusFilter != '') {
            if ($request->statusFilter == 'Đang sử dụng') {
                $query->whereNull('deleted_at');
            } elseif ($request->statusFilter == 'Không sử dụng') {
                $query->whereNotNull('deleted_at');
            }
        }

        $data = $query->withTrashed()->latest('id')->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.loainguyenlieu.body-list', compact('data'))->render(),
            ]);
        }

        return view('admin.loainguyenlieu.list', [
            'data' => $data,
            'route' => route('loai-nguyen-lieu.index'),
            'tableId' => 'list-container',
            'searchInputId' => 'search-name',
        ]);
    }

    public function create()
    {
        return view('admin.loainguyenlieu.create');
    }

    public function store(StoreLoaiNguyenLieuRequest $request)
    {
        LoaiNguyenLieu::create($request->validated());

        return redirect()->route('loai-nguyen-lieu.index')->with('success', 'Thêm loại nguyên liệu thành công!');
    }

    public function show(LoaiNguyenLieu $loaiNguyenLieu)
    {
        return view('admin.loainguyenlieu.detail', compact('loaiNguyenLieu'));
    }

    public function edit(LoaiNguyenLieu $loaiNguyenLieu)
    {
        return view('admin.loainguyenlieu.edit', compact('loaiNguyenLieu'));
    }

    public function update(UpdateLoaiNguyenLieuRequest $request, LoaiNguyenLieu $loaiNguyenLieu)
    {
        $loaiNguyenLieu->update($request->validated());

        return back()->with('success', 'Cập nhật loại nguyên liệu thành công!');
    }

    public function destroy(LoaiNguyenLieu $loaiNguyenLieu)
    {
        $loaiNguyenLieu->delete();

        return redirect()->route('loai-nguyen-lieu.index')->with('success', 'Ngừng sử dụng loại nguyên liệu!');
    }

    public function restore($id)
    {
        $loaiNguyenLieu = LoaiNguyenLieu::withTrashed()->findOrFail($id);
        $loaiNguyenLieu->restore();

        return redirect()->route('loai-nguyen-lieu.index')->with('success', 'Khôi phục loại nguyên liệu thành công!');
    }

    public function export()
    {
        return Excel::download(new LoaiNguyenLieuExport, 'LoaiNguyenLieu.xlsx');
    }
}
