<?php

namespace App\Http\Controllers;

use App\Exports\NhaCungCapExport;
use App\Imports\NhaCungCapImport;
use App\Models\NhaCungCap;
use App\Http\Requests\StoreNhaCungCapRequest;
use App\Http\Requests\UpdateNhaCungCapRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class NhaCungCapController extends Controller
{
    public function index(Request $request)
    {
        $query = NhaCungCap::query();


        $searchInputId = 'searchInput';
        if ($request->has('searchInput') && $request->searchInput != '') {
            $query->where('ten_nha_cung_cap', 'like', '%' . $request->searchInput . '%');
        }

        if ($request->has('statusFilter') && $request->statusFilter != '') {
            if ($request->statusFilter === 'Đang hoạt động') {
                $query->whereNull('deleted_at');
            } elseif ($request->statusFilter === 'Đã ngừng hoạt động') {
                $query->whereNotNull('deleted_at');
            }
        }

        $data = $query->withTrashed()->latest('id')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.nhacungcap.index', compact('data', 'searchInputId'))->render(),
                'pagination' => (string) $data->links('pagination::bootstrap-5')->toHtml() // Trả về phân trang
            ]);
        }

        return view('admin.nhacungcap.index', [
            'data' => $data,
            'route' => route('nha-cung-cap.index'),
            'tableId' => 'list-container',
            'searchInputId' => 'searchInput',
        ]);
    }

    public function create()
    {
        return view('admin.nhacungcap.create');
    }

    public function store(StoreNhaCungCapRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('hinhAnh')) {
            $data['hinhAnh'] = $request->file('hinhAnh')->store('NhaCungCapImg', 'public');
        }

        NhaCungCap::create($data);

        return redirect()->route('nha-cung-cap.index')->with('success', 'Thêm nhà cung cấp thành công!');
    }

    public function show($id)
    {
        $nhaCungCap = NhaCungCap::withTrashed()->findOrFail($id);
        return view('admin.nhacungcap.detail', compact('nhaCungCap'));
    }

    public function edit($id)
    {
        $nhaCungCap = NhaCungCap::withTrashed()->findOrFail($id);
        return view('admin.nhacungcap.edit', compact('nhaCungCap'));
    }

    public function update(UpdateNhaCungCapRequest $request, $id)
    {
        $nhaCungCap = NhaCungCap::withTrashed()->findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('hinhAnh')) {
            if ($nhaCungCap->hinhAnh) {
                Storage::disk('public')->delete($nhaCungCap->hinhAnh);
            }
            $data['hinhAnh'] = $request->file('hinhAnh')->store('NhaCungCapImg', 'public');
        }

        $nhaCungCap->update($data);

        return back()->with('success', 'Cập nhật nhà cung cấp thành công!');
    }

    public function destroy(NhaCungCap $nhaCungCap)
    {
        // Kiểm tra xem có phiếu nhập kho nào liên kết với nhà cung cấp này và trạng thái là "cho_duyet"
        $kiemTraPhieuNhap = $nhaCungCap->phieuNhapKhos()
            ->where('trang_thai', 'cho_duyet')
            ->exists();

        $kiemTraPhieuXuat = $nhaCungCap->phieuXuatKhos()
            ->where('trang_thai', 'cho_duyet')
            ->exists();

        // Nếu cả phiếu nhập kho và phiếu xuất kho đều đang chờ duyệt
        if ($kiemTraPhieuNhap && $kiemTraPhieuXuat) {
            return redirect()->route('nha-cung-cap.index')
                ->with('error', 'Không thể xóa nhà cung cấp vì đang có phiếu nhập kho và phiếu xuất kho đều ở trạng thái chờ duyệt.');
        }

        // Nếu chỉ có phiếu nhập kho đang chờ duyệt
        if ($kiemTraPhieuNhap) {
            return redirect()->route('nha-cung-cap.index')
                ->with('error', 'Không thể xóa nhà cung cấp vì đang có phiếu nhập kho ở trạng thái chờ duyệt.');
        }

        // Nếu chỉ có phiếu xuất kho đang chờ duyệt
        if ($kiemTraPhieuXuat) {
            return redirect()->route('nha-cung-cap.index')
                ->with('error', 'Không thể xóa nhà cung cấp vì đang có phiếu xuất kho ở trạng thái chờ duyệt.');
        }

        // Xóa nhà cung cấp nếu không có phiếu nhập kho và phiếu xuất kho nào đang chờ duyệt
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
        return Excel::download(new NhaCungCapExport, 'NhaCungCap.xlsx');
    }
    public function importNhaCungCap(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $importer = new \App\Imports\NhaCungCapImport();
        \Maatwebsite\Excel\Facades\Excel::import($importer, $request->file('file'));

        if (!empty($importer->errors)) {
            return back()
                ->with('error', 'Lỗi dữ liệu bị trùng hoặc không hợp lệ.')
                ->with('import_errors', $importer->errors);
        }

        return back()->with('success', 'Nhập dữ liệu thành công!');
    }
}
