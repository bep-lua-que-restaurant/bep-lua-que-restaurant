<?php

namespace App\Http\Controllers;

use App\Exports\ChucVuExport;
use App\Imports\ChucVuImport;
use App\Models\ChucVu;
use App\Http\Requests\StoreChucVuRequest;
use App\Http\Requests\UpdateChucVuRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ChucVuController extends Controller
{
    public function index(Request $request)
    {
        $searchInput = $request->input('searchInput');
        $statusFilter = $request->input('statusFilter');

        $query = ChucVu::query();

        if ($searchInput) {
            $query->where('ten_chuc_vu', 'like', '%' . $searchInput . '%');
        }

        if ($statusFilter && $statusFilter !== 'Tất cả') {
            if ($statusFilter === 'Đang hoạt động') {
                $query->whereNull('deleted_at');
            } else if ($statusFilter === 'Đã ngừng hoạt động') {
                $query->whereNotNull('deleted_at');
            }
        }
        $data = $query->get();
        return view('admin.chucvu.list', compact('data'));
    }

    public function create()
    {
        return view('admin.chucvu.create');
    }

    public function store(StoreChucVuRequest $request)
    {
        $data = $request->validated();
        ChucVu::create($data);

        return redirect()->route('chuc-vu.index')->with('success', 'Thêm chức vụ thành công!');
    }

    public function show($id)
    {
        $chucVu = ChucVu::withTrashed()->findOrFail($id);
        return view('admin.chucvu.detail', compact('chucVu'));
    }

    public function edit($id)
    {
        $chucVu = ChucVu::withTrashed()->findOrFail($id);
        return view('admin.chucvu.edit', compact('chucVu'));
    }

    public function update(UpdateChucVuRequest $request, $id)
    {
    $chucVu = ChucVu::withTrashed()->findOrFail($id);

    $data = $request->validated();
    $chucVu->update($data);

    return back()->with('success', 'Cập nhật chức vụ thành công!');
    }


    public function destroy(ChucVu $chucVu)
    {
        // Kiểm tra xem còn nhân viên nào đang làm việc với chức vụ này không
        $nhanVienDangLamViec = \App\Models\NhanVien::where('chuc_vu_id', $chucVu->id)
            ->where('trang_thai', 'dang_lam_viec')
            ->count();
    
        if ($nhanVienDangLamViec > 0) {
            return redirect()->route('chuc-vu.index')
                ->with('error', 'Không thể xóa chức vụ vì vẫn còn nhân viên đang làm việc!');
        }
    
        // Nếu chỉ còn nhân viên nghỉ việc hoặc không còn ai, thì cho phép xóa
        $chucVu->delete();
    
        return redirect()->route('chuc-vu.index')
            ->with('success', 'Xóa chức vụ thành công!');
    }
    

    public function restore($id)
    {
        $chucVu = ChucVu::withTrashed()->findOrFail($id);
        $chucVu->restore();

        return redirect()->route('chuc-vu.index')->with('success', 'Khôi phục chức vụ thành công!');
    }

    public function export()
    {
        return Excel::download(new ChucVuExport, 'ChucVu.xlsx');
    }

    public function importChucVu(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new ChucVuImport, $request->file('file'));

        return back()->with('success', 'Nhập dữ liệu thành công!');
    }
}
