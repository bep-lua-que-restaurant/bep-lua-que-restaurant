<?php

namespace App\Http\Controllers;

use App\Events\ThucDonUpdated;
use App\Exports\MonAnExport;
use App\Imports\MonAnImport;
use App\Models\DanhMucMonAn;
use App\Models\HinhAnhMonAn;
use App\Models\MonAn;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMonAnRequest;
use App\Http\Requests\UpdateMonAnRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MonAnController extends Controller
{
    public function index(Request $request)
    {
        $query = MonAn::with(['danhMuc', 'hinhAnhs'])->withTrashed();

        $query->whereHas('danhMuc', function ($q) {
            $q->whereNull('deleted_at');
        });

        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten', 'like', '%' . $request->ten . '%');
        }

        if ($request->has('statusFilter') && $request->statusFilter != '') {
            if ($request->statusFilter == 'Đang kinh doanh') {
                $query->whereNull('deleted_at');
            } elseif ($request->statusFilter == 'Ngừng kinh doanh') {
                $query->whereNotNull('deleted_at');
            }
        }

        $data = $query->withTrashed()->latest('id')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.monan.body-list', compact('data'))->render(),
            ]);
        }

        return view('admin.monan.list', [
            'data' => $data,
            'route' => route('mon-an.index'),
            'tableId' => 'list-container',
            'searchInputId' => 'search-name',
        ]);
    }

    public function create()
    {
        $danhMucs = DanhMucMonAn::whereNull('deleted_at')->get();
        return view('admin.monan.create', compact('danhMucs'));
    }

    public function store(StoreMonAnRequest $request)
    {
        $danhMuc = DanhMucMonAn::find($request->danh_muc_mon_an_id);
        if (!$danhMuc || $danhMuc->deleted_at !== null) {
            return redirect()->back()->withErrors(['danh_muc_mon_an_id' => 'Danh mục này không tồn tại hoặc đã bị xóa.']);
        }

        $validatedData = $request->validated();
        $validatedData['trang_thai'] = 'dang_ban';

        DB::beginTransaction();
        try {
            $monAn = MonAn::create($validatedData);

            if ($request->hasFile('hinh_anh')) {
                foreach ($request->file('hinh_anh') as $image) {
                    $path = $image->store('mon_an_images', 'public');
                    HinhAnhMonAn::create([
                        'mon_an_id' => $monAn->id,
                        'hinh_anh' => $path
                    ]);
                }
            }

            DB::commit();
            broadcast(new ThucDonUpdated($monAn))->toOthers();

            return redirect()->route('mon-an.index')->with('success', 'Món ăn đã được thêm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Đã xảy ra lỗi khi thêm món ăn.']);
        }
    }

    public function show(MonAn $monAn)
    {
        $monAn->load('danhMuc', 'hinhAnhs');
        return view('admin.monan.detail', compact('monAn'));
    }

    public function edit(MonAn $monAn)
    {
        $danhMucs = DanhMucMonAn::whereNull('deleted_at')->get();
        return view('admin.monan.edit', compact('monAn', 'danhMucs'));
    }

    public function update(UpdateMonAnRequest $request, MonAn $monAn)
    {
        $data = $request->validated();
        $monAn->update($data);
    
        // Nếu có ảnh mới upload
        if ($request->hasFile('hinh_anh')) {
            // Xóa tất cả hình ảnh cũ
            foreach ($monAn->hinhAnhs as $image) {
                Storage::delete('public/' . $image->hinh_anh);
                $image->delete();
            }
    
            // Lưu hình ảnh mới
            foreach ($request->file('hinh_anh') as $file) {
                $path = $file->store('mon_an_images', 'public');
                $monAn->hinhAnhs()->create(['hinh_anh' => $path]);
            }
        }
    
        // Xử lý xóa hình ảnh theo yêu cầu thủ công (nếu có remove_images)
        if ($request->has('remove_images') && is_array($request->remove_images)) {
            foreach ($request->remove_images as $imageId) {
                $image = $monAn->hinhAnhs()->find($imageId);
                if ($image) {
                    Storage::delete('public/' . $image->hinh_anh);
                    $image->delete();
                }
            }
        }
    
        // Broadcast event
        broadcast(new ThucDonUpdated($monAn))->toOthers();
    
        return redirect()->route('mon-an.index')->with('success', 'Cập nhật món ăn thành công.');
    }
    

    public function xoaHinhAnh($hinhAnhId)
    {
        $hinhAnh = HinhAnhMonAn::findOrFail($hinhAnhId);
        Storage::disk('public')->delete($hinhAnh->hinh_anh);
        $hinhAnh->delete();
        return response()->json(['success' => true]);
    }

    public function destroy(MonAn $monAn)
    {
        $monAn->update(['trang_thai' => 'ngung_ban']);
        $monAn->delete();

        $monAnDeleted = new MonAn();
        $monAnDeleted->id = $monAn->id;
        $monAnDeleted->deleted_at = now();

        broadcast(new ThucDonUpdated($monAnDeleted))->toOthers();

        return redirect()->route('mon-an.index')->with('success', 'Xóa món ăn thành công.');
    }

    public function restore($id)
    {
        $monAn = MonAn::withTrashed()->findOrFail($id);

        if ($monAn->danhMuc && $monAn->danhMuc->deleted_at !== null) {
            return redirect()->back()->withErrors(['error' => 'Danh mục của món ăn đã bị xóa mềm. Vui lòng khôi phục danh mục trước.']);
        }

        $monAn->restore();
        $monAn->update(['trang_thai' => 'dang_ban']);

        broadcast(new ThucDonUpdated($monAn))->toOthers();

        return redirect()->route('mon-an.index')->with('success', 'Khôi phục món ăn thành công.');
    }

    public function exportMonAn()
    {
        return Excel::download(new MonAnExport, 'MonAn.xlsx');
    }

    public function importMonAn(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new MonAnImport, $request->file('file'));

        return back()->with('success', 'Nhập dữ liệu thành công!');
    }
}
