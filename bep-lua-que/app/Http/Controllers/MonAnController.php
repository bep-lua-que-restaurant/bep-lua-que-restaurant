<?php

namespace App\Http\Controllers;

use App\Events\ThucDonUpdated;
use App\Exports\MonAnExport;
use App\Imports\MonAnImport;
use App\Models\CongThucMonAn;
use App\Models\DanhMucMonAn;
use App\Models\HinhAnhMonAn;
use App\Models\MonAn;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMonAnRequest;
use App\Http\Requests\UpdateMonAnRequest;
use App\Models\NguyenLieu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class MonAnController extends Controller
{


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = MonAn::with(['danhMuc'])->withTrashed()->orderByDesc('created_at');;

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
                
                    // Nút xem chi tiết luôn hiện
                    $html .= '<a href="' . route('mon-an.show', $row->id) . '" class="btn btn-info btn-sm p-2 m-2" title="Xem chi tiết">
                                <i class="fa fa-eye"></i></a>';
                
                    // Chỉ hiện nút chỉnh sửa nếu chưa bị xóa mềm
                    if (is_null($row->deleted_at)) {
                        $html .= '<a href="' . route('mon-an.edit', $row->id) . '" class="btn btn-warning btn-sm p-2 m-2" title="Chỉnh sửa">
                                    <i class="fa fa-edit"></i></a>';
                    }
                
                    // Nếu đã bị xoá => hiện nút khôi phục
                    if ($row->deleted_at) {
                        $html .= '<form action="' . route('mon-an.restore', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field()
                            . '<button type="submit" class="btn btn-success btn-sm p-2 m-2" title="Khôi phục">
                                <i class="fa fa-recycle"></i></button>'
                            . '</form>';
                    } else {
                        // Nếu chưa bị xoá => hiện nút xoá
                        $html .= '<form action="' . route('mon-an.destroy', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field()
                            . method_field('DELETE')
                            . '<button type="submit" class="btn btn-danger btn-sm p-2 m-2" title="Xóa">
                                <i class="fa fa-trash"></i></button>'
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

        // Lấy tất cả nguyên liệu, bao gồm cả đã bị soft delete
        // $nguyenLieus = NguyenLieu::withTrashed()
        //     ->select('id', 'ten_nguyen_lieu', 'don_vi_ton')
        //     ->get();

        return view('admin.monan.create', compact('danhMucs',));
    }



    public function store(StoreMonAnRequest $request)
    {
        // dd($request->all());

        $danhMuc = DanhMucMonAn::find($request->danh_muc_mon_an_id);
        if (!$danhMuc || $danhMuc->deleted_at !== null) {
            return redirect()->back()->withErrors(['danh_muc_mon_an_id' => 'Danh mục này không tồn tại hoặc đã bị xóa.'])->withInput();
        }

        DB::beginTransaction();

        try {
            $data = $request->only(['ten', 'danh_muc_mon_an_id', 'mo_ta', 'gia', 'thoi_gian_nau']);
            $data['trang_thai'] = 'dang_ban';

            $monAn = MonAn::create($data);

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
            dd($e->getMessage(), $e->getTraceAsString()); // xem lỗi cụ thể ở đây
            return redirect()->back()->withErrors(['error' => 'Đã xảy ra lỗi khi thêm món ăn.'])->withInput();
        }
    }



    public function show($monAnId)
    {
        $monAn = MonAn::withTrashed()->with(['danhMuc', 'hinhAnhs'])->findOrFail($monAnId);

        return view('admin.monan.detail', compact('monAn'));
    }




    public function edit($id)
    {
        // Chỉ lấy món ăn chưa bị xoá
        $monAn = MonAn::findOrFail($id);

        // Lấy danh sách danh mục chưa bị xoá
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

        return redirect()->back()->with('success', 'Cập nhật món ăn thành công.');
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
        // Lấy các hóa đơn chứa món ăn này
        $hoaDonChuaThanhToan = \App\Models\HoaDon::whereHas('chiTietHoaDons', function ($query) use ($monAn) {
            $query->where('mon_an_id', $monAn->id);
        })
            ->whereHas('hoaDonBans', function ($query) {
                $query->where('trang_thai', '!=', 'da_thanh_toan');
            })
            ->first();

        // Nếu tồn tại hóa đơn chưa thanh toán chứa món ăn này thì không cho xóa
        if ($hoaDonChuaThanhToan) {
            $message = 'Không thể xóa món ăn vì món ăn đang được khách sử dụng';

            // ✅ Chỉ thêm phần này vào
            if (request()->ajax()) {
                return response()->json(['message' => $message], 422);
            }


            return redirect()->route('mon-an.index')
                ->with('error', 'Không thể xóa món ăn vì món ăn đang được khách sử dụng');
        }

        // Nếu không có trong hóa đơn chưa thanh toán thì cho phép xóa
        $monAn->update(['trang_thai' => 'ngung_ban']);
        $monAn->delete();

        $monAnDeleted = new MonAn();
        $monAnDeleted->id = $monAn->id;
        $monAnDeleted->deleted_at = now();

        broadcast(new ThucDonUpdated($monAnDeleted))->toOthers();

        return redirect()->route('mon-an.index')
            ->with('success', 'Xóa món ăn thành công.');
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
