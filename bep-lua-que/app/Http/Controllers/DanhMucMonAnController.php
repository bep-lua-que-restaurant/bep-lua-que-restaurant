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
use Yajra\DataTables\Facades\DataTables;

class DanhMucMonAnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DanhMucMonAn::query()->withTrashed();
    
            return DataTables::of($query)
                ->addIndexColumn() // Thêm cột số thứ tự
                ->addColumn('trang_thai', function ($row) {
                    if ($row->deleted_at != null) {
                        return '<div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Đã ngừng kinh doanh</div>';
                    } else {
                        return '<div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Đang kinh doanh</div>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex align-items-center">';
                
                    // Nút xem chi tiết
                    $html .= '<a href="' . route('phieu-nhap-kho.show', $row->id) . '" class="btn btn-info btn-sm p-2 m-2" title="Xem chi tiết">
                                <i class="fa fa-eye"></i>
                              </a>';
                
                    // Nút sửa
                    $html .= '<a href="' . route('phieu-nhap-kho.edit', $row->id) . '" class="btn btn-warning btn-sm p-2 m-2" title="Chỉnh sửa">
                                <i class="fa fa-edit"></i>
                              </a>';
                
                    // Nút xóa hoặc khôi phục tuỳ trạng thái soft delete
                    if ($row->deleted_at) {
                        $html .= '<form action="' . route('phieu-nhap-kho.restore', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field()
                            . '<button type="submit" class="btn btn-success btn-sm p-2 m-2" title="Khôi phục">
                                    <i class="fa fa-recycle"></i>
                               </button>'
                            . '</form>';
                    } else {
                        $html .= '<form action="' . route('phieu-nhap-kho.destroy', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field()
                            . method_field('DELETE')
                            . '<button type="submit" class="btn btn-danger btn-sm p-2 m-2" title="Xóa">
                                    <i class="fa fa-trash"></i>
                               </button>'
                            . '</form>';
                    }
                
                    $html .= '</div>';
                    return $html;
                })
                
                ->rawColumns(['trang_thai', 'action']) // Chỉ giữ lại các cột có HTML
                ->make(true);
        }
    
        return view('admin.danhmuc.list', [
            'route' => route('danh-muc-mon-an.index'),
            'tableId' => 'list-container',
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

        return redirect()->route('danh-muc-mon-an.index');
    }

    public function restore($id)
    {
        $danhMucMonAn = DanhMucMonAn::withTrashed()->findOrFail($id);
        $danhMucMonAn->restore();

        return redirect()->route('danh-muc-mon-an.index');
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
