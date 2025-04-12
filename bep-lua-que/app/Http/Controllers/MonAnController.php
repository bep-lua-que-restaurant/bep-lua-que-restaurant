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
use Yajra\DataTables\DataTables;

class MonAnController extends Controller
{


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = MonAn::with(['danhMuc'])->withTrashed();
    
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('trang_thai', function ($row) {
                    return $row->deleted_at
                        ? '<div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Ngừng bán</div>'
                        : '<div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Đang bán</div>';
                })
                ->addColumn('danh_muc', function ($row) {
                    return $row->danhMuc ? $row->danhMuc->ten : 'Không có';
                })
                ->addColumn('gia', function ($row) {
                    return number_format($row->gia, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex align-items-center">';
    
                    $html .= '<a href="' . route('mon-an.show', $row->id) . '" class="btn btn-info btn-sm p-2 m-2" title="Xem chi tiết"><i class="fa fa-eye"></i></a>';
                    $html .= '<a href="' . route('mon-an.edit', $row->id) . '" class="btn btn-warning btn-sm p-2 m-2" title="Chỉnh sửa"><i class="fa fa-edit"></i></a>';
    
                    if ($row->deleted_at) {
                        $html .= '<form action="' . route('mon-an.restore', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field()
                            . '<button type="submit" class="btn btn-success btn-sm p-2 m-2" title="Khôi phục"><i class="fa fa-recycle"></i></button>'
                            . '</form>';
                    } else {
                        $html .= '<form action="' . route('mon-an.destroy', $row->id) . '" method="POST" style="display:inline;">'
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
    
        return view('admin.monan.list', [
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
