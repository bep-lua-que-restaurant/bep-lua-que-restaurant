<?php

namespace App\Http\Controllers;

use App\Exports\MonAnExport;
use App\Imports\MonAnImport;
use App\Models\DanhMucMonAn;
use App\Models\HinhAnhMonAn;
use App\Models\MonAn;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMonAnRequest;
use App\Http\Requests\UpdateMonAnRequest;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MonAnController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tất cả món ăn (bao gồm cả món đã bị xóa mềm) nhưng bỏ món có danh mục bị xóa mềm
        $query = MonAn::with(['danhMuc', 'hinhAnhs'])->withTrashed();
    
        // Loại bỏ món ăn có danh mục đã bị xóa mềm (deleted_at != NULL)
        $query->whereHas('danhMuc', function ($q) {
            $q->whereNull('deleted_at'); // Chỉ lấy danh mục chưa bị xóa mềm
        });
    
        // Nếu có tìm kiếm theo tên
        if ($request->has('ten') && !empty($request->ten)) {
            $query->where('ten', 'like', '%' . $request->ten . '%');
        }
    
        // Lấy danh sách món ăn với phân trang
        $data = $query->latest('id')->paginate(15);
    
        // Nếu là request AJAX, trả về HTML của danh sách món ăn
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.monan.body-list', compact('data'))->render(),
            ]);
        }
    
        // Trả về view danh sách món ăn
        return view('admin.monan.list', [
            'data' => $data,
            'route' => route('mon-an.index'),
            'tableId' => 'list-container',
            'searchInputId' => 'search-name',
        ]);
    }
    


    public function create()
    {
        // Chỉ lấy danh mục chưa bị xóa mềm
        $danhMucs = DanhMucMonAn::whereNull('deleted_at')->get();
        return view('admin.monan.create', compact('danhMucs'));
    }

    public function store(StoreMonAnRequest $request)
    {
        // Kiểm tra xem danh mục có bị xóa mềm không
        $danhMuc = DanhMucMonAn::find($request->danh_muc_mon_an_id);
        if (!$danhMuc || $danhMuc->deleted_at !== null) {
            return redirect()->back()->withErrors(['danh_muc_mon_an_id' => 'Danh mục này không tồn tại hoặc đã bị xóa.']);
        }

        $monAn = MonAn::create($request->validated());

        if ($request->hasFile('hinh_anh')) {
            foreach ($request->file('hinh_anh') as $image) {
                $path = $image->store('mon_an_images', 'public');
                HinhAnhMonAn::create(['mon_an_id' => $monAn->id, 'hinh_anh' => $path]);
            }
        }

        return redirect()->route('mon-an.index')->with('success', 'Món ăn đã được thêm thành công!');
    }

    public function show(MonAn $monAn)
    {
        // Load danh mục món ăn + hình ảnh
        $monAn->load('danhMuc', 'hinhAnhs');

        return view('admin.monan.detail', compact('monAn'));
    }

    public function edit(MonAn $monAn)
    {
        // Chỉ lấy danh mục chưa bị xóa mềm
        $danhMucs = DanhMucMonAn::whereNull('deleted_at')->get();
        return view('admin.monan.edit', compact('monAn', 'danhMucs'));
    }

    public function update(UpdateMonAnRequest $request, MonAn $monAn)
    {
        // Kiểm tra xem danh mục có bị xóa mềm không
        $danhMuc = DanhMucMonAn::find($request->danh_muc_mon_an_id);
        if (!$danhMuc || $danhMuc->deleted_at !== null) {
            return redirect()->back()->withErrors(['danh_muc_mon_an_id' => 'Danh mục này không tồn tại hoặc đã bị xóa.']);
        }

        $monAn->update($request->validated());

        // Cập nhật hình ảnh
        if ($request->hasFile('hinh_anh')) {
            foreach ($monAn->hinhAnhs as $image) {
                Storage::disk('public')->delete($image->hinh_anh);
                $image->delete();
            }

            foreach ($request->file('hinh_anh') as $image) {
                $path = $image->store('mon_an_images', 'public');
                HinhAnhMonAn::create(['mon_an_id' => $monAn->id, 'hinh_anh' => $path]);
            }
        }

        return redirect()->route('mon-an.index')->with('success', 'Cập nhật món ăn thành công!');
    }

    public function destroy(MonAn $monAn)
    {
        // Xóa tất cả ảnh món ăn trước khi xóa món ăn
        foreach ($monAn->hinhAnhs as $image) {
            Storage::disk('public')->delete($image->hinh_anh);
            $image->delete();
        }

        $monAn->delete();

        return redirect()->route('mon-an.index')->with('success', 'Xóa món ăn thành công!');
    }
    public function restore($id)
    {
        // Tìm món ăn bao gồm cả những món đã bị xóa mềm
        $monAn = MonAn::withTrashed()->findOrFail($id);

        // Kiểm tra xem danh mục của món ăn có bị xóa mềm không
        if ($monAn->danhMuc && $monAn->danhMuc->deleted_at !== null) {
            return redirect()->back()->withErrors(['error' => 'Danh mục của món ăn đã bị xóa mềm. Vui lòng khôi phục danh mục trước.']);
        }

        // Khôi phục món ăn
        $monAn->restore();

        return redirect()->route('mon-an.index')->with('success', 'Khôi phục món ăn thành công!');
    }
    public function xoaHinhAnh($id)
{
    $hinhAnh = HinhAnhMonAn::find($id);

    if (!$hinhAnh) {
        return response()->json(['error' => 'Không tìm thấy ảnh'], 404);
    }

    // Xóa file trong thư mục storage
    if (Storage::disk('public')->exists($hinhAnh->hinh_anh)) {
        Storage::disk('public')->delete($hinhAnh->hinh_anh);
    }

    // Xóa ảnh trong database
    $hinhAnh->delete();

    return response()->json(['success' => 'Ảnh đã được xóa thành công']);
}

    /**
     * Xuất danh sách món ăn ra file Excel
     */
    public function exportMonAn()
    {
        return Excel::download(new MonAnExport, 'MonAn.xlsx');
    }
     /**
     * Nhập danh sách món ăn từ file Excel
     */
    public function importMonAn(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        try {
            Excel::import(new MonAnImport, $request->file('file'));

            return back()->with('success', 'Nhập dữ liệu món ăn thành công!');
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Lỗi khi nhập dữ liệu: ' . $e->getMessage()]);
        }
    }
}
