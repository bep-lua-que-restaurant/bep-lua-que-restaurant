<?php

namespace App\Http\Controllers;

use App\Events\ThucDonUpdated;
use App\Exports\MonAnExport;
use App\Imports\MonAnImport;
use App\Models\DanhMucMonAn;
use App\Models\HinhAnhMonAn;
use App\Models\MonAn;
use App\Models\NguyenLieu;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMonAnRequest;
use App\Http\Requests\UpdateMonAnRequest;
use App\Models\CongThucMonAn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class MonAnController extends Controller
{
    public function index(Request $request)
{
    // Tạo query lấy tất cả nguyên liệu (bao gồm cả bị xóa mềm) và danh mục chưa bị xóa mềm
    $query = MonAn::with(['danhMuc', 'hinhAnhs'])->withTrashed();

    // Chỉ lấy nguyên liệu có danh mục chưa bị xóa mềm
    $query->whereHas('danhMuc', function ($q) {
        $q->whereNull('deleted_at'); // Chỉ lấy danh mục chưa bị xóa mềm
    });

    // Lọc theo tên nguyên liệu
    if ($request->has('ten') && $request->ten != '') {
        $query->where('ten', 'like', '%' . $request->ten . '%');
    }

    // Lọc theo trạng thái nguyên liệu (Đang kinh doanh / Ngừng kinh doanh)
    if ($request->has('statusFilter') && $request->statusFilter != '') {
        if ($request->statusFilter == 'Đang kinh doanh') {
            $query->whereNull('deleted_at');
        } elseif ($request->statusFilter == 'Ngừng kinh doanh') {
            $query->whereNotNull('deleted_at');
        }
    }

    // Phân trang và lấy danh sách nguyên liệu
    $data = $query->withTrashed()->latest('id')->paginate(15);

    // Nếu là request AJAX, trả về HTML của danh sách nguyên liệu
    if ($request->ajax()) {
        return response()->json([
            'html' => view('admin.monan.body-list', compact('data'))->render(),
        ]);
    }

    // Trả về view danh sách nguyên liệu
    return view('admin.monan.list', [
        'data' => $data,
        'route' => route('mon-an.index'), // URL route cho AJAX
        'tableId' => 'list-container', // ID của bảng
        'searchInputId' => 'search-name', // ID của ô tìm kiếm
    ]);
}





   
    public function create()
    {
        // Lấy danh mục chưa bị xóa mềm
        $danhMucs = DanhMucMonAn::whereNull('deleted_at')->get();

        // Lấy danh sách nguyên liệu để chọn
        $nguyenLieus = NguyenLieu::all();
        // dd($nguyenLieus); // Kiểm tra dữ liệu

        return view('admin.monan.create', compact('danhMucs', 'nguyenLieus'));
    }

    public function store(StoreMonAnRequest $request)
    {
        // Kiểm tra danh mục có bị xóa mềm không
        $danhMuc = DanhMucMonAn::find($request->danh_muc_mon_an_id);
        if (!$danhMuc || $danhMuc->deleted_at !== null) {
            return redirect()->back()->withErrors(['danh_muc_mon_an_id' => 'Danh mục này không tồn tại hoặc đã bị xóa.']);
        }

        // Thêm trạng thái mặc định nếu không có
        $validatedData = $request->validated();
        $validatedData['trang_thai'] = 'dang_ban'; // Mặc định là đang bán

        // Tạo món ăn
        DB::beginTransaction(); // Bắt đầu transaction
        try {
            $monAn = MonAn::create($validatedData);

            // Xử lý hình ảnh nếu có
            if ($request->hasFile('hinh_anh')) {
                foreach ($request->file('hinh_anh') as $image) {
                    $path = $image->store('mon_an_images', 'public');
                    HinhAnhMonAn::create([
                        'mon_an_id' => $monAn->id,
                        'hinh_anh' => $path
                    ]);
                }
            }

            // Thêm nguyên liệu vào món ăn
            if ($request->has('nguyen_lieu_id')) {
                foreach ($request->nguyen_lieu_id as $key => $nguyenLieuId) {
                    CongThucMonAn::create([
                        'mon_an_id' => $monAn->id,
                        'nguyen_lieu_id' => $nguyenLieuId,
                        'so_luong' => $request->so_luong[$key],
                        'don_vi_tinh' => $request->don_vi_tinh[$key],
                    ]);
                }
            }

            DB::commit(); // Lưu thay đổi
            broadcast(new ThucDonUpdated($monAn))->toOthers(); // Phát sự kiện khi cập nhật

            return redirect()->route('mon-an.index')->with('success', 'Món ăn đã được thêm thành công!');
        } catch (\Exception $e) {
            DB::rollBack(); // Hoàn tác nếu có lỗi
            return redirect()->back()->withErrors(['error' => 'Đã xảy ra lỗi khi thêm món ăn.']);
        }
    }



    public function show(MonAn $monAn)
{
    // Load danh mục món ăn, hình ảnh và nguyên liệu kèm thông tin trong bảng trung gian
    $monAn->load('danhMuc', 'hinhAnhs', 'nguyenLieus');

    return view('admin.monan.detail', compact('monAn'));
}


    public function edit(MonAn $monAn)
    {
        // Chỉ lấy danh mục chưa bị xóa mềm
        $danhMucs = DanhMucMonAn::whereNull('deleted_at')->get();
        // Lấy danh sách nguyên liệu liên quan đến món ăn
        $nguyenLieus = NguyenLieu::all();
        $monAnNguyenLieus = $monAn->nguyenLieus()->get();

        return view('admin.monan.edit', compact('monAn', 'danhMucs', 'nguyenLieus', 'monAnNguyenLieus'));
    }


    // Cập nhật món ăn
    public function update(UpdateMonAnRequest $request, MonAn $monAn)
    {
        $data = $request->validated();

        // Cập nhật các trường thông tin cơ bản của món ăn
        $monAn->update($data);

        // Cập nhật nguyên liệu món ăn
        $monAn->nguyenLieus()->detach();
        if ($request->has('nguyen_lieu_id')) {
            foreach ($request->nguyen_lieu_id as $index => $nguyenLieuId) {
                if ($nguyenLieuId) {
                    $monAn->nguyenLieus()->attach($nguyenLieuId, [
                        'so_luong' => $request->so_luong[$index] ?? 0,
                        'don_vi_tinh' => $request->don_vi_tinh[$index] ?? '',
                    ]);
                }
            }
        }

        // Xử lý xóa hình ảnh nếu có hình ảnh cần xóa
        if ($request->has('remove_images') && is_array($request->remove_images)) 
        {
            foreach ($request->remove_images as $imageId) {
                $image = $monAn->hinhAnhs()->find($imageId);
                if ($image)
                 {
                    
                     Storage::delete('public/' . $image->hinh_anh);
                    $image->delete();
                }
            }
        }

        // Xử lý thêm hình ảnh mới nếu người dùng chọn
        if ($request->hasFile('hinh_anh')) {
            foreach ($request->file('hinh_anh') as $file) {
                $path = $file->store('mon_an_images', 'public');
                $monAn->hinhAnhs()->create(['hinh_anh' => $path]);
            }
        }

        broadcast(new ThucDonUpdated($monAn))->toOthers();

        return redirect()->route('mon-an.index')->with('success', 'Cập nhật món ăn thành công.');
    }



    // Xóa ảnh hiện tại
    public function xoaHinhAnh($hinhAnhId)
    {
        // Tìm ảnh theo ID
        $hinhAnh = HinhAnhMonAn::findOrFail($hinhAnhId);

        // Xóa ảnh khỏi thư mục public
        Storage::disk('public')->delete($hinhAnh->hinh_anh);

        // Xóa ảnh khỏi cơ sở dữ liệu
        $hinhAnh->delete();

        // Trả về phản hồi thành công
        return response()->json(['success' => true]);
    }


    public function destroy(MonAn $monAn)
    {
        // Cập nhật trạng thái thành 'ngung_ban' trước khi xóa
        $monAn->update(['trang_thai' => 'ngung_ban']);
    
        // Xóa mềm món ăn
        $monAn->delete();
    
        // Phát sự kiện cập nhật thực đơn
        $monAnDeleted = new MonAn();
        $monAnDeleted->id = $monAn->id;
        $monAnDeleted->deleted_at = now(); // Giả lập đã bị xóa
    
        broadcast(new ThucDonUpdated($monAnDeleted))->toOthers();
    
        return redirect()->route('mon-an.index')->with('success', 'Xóa món ăn thành công .');
    }
    
    public function restore($id)
{
    // Tìm món ăn bao gồm cả những món đã bị xóa mềm
    $monAn = MonAn::withTrashed()->findOrFail($id);

    // Kiểm tra xem danh mục của món ăn có bị xóa mềm không
    if ($monAn->danhMuc && $monAn->danhMuc->deleted_at !== null) {
        return redirect()->back()->withErrors(['error' => 'Danh mục của món ăn đã bị xóa mềm. Vui lòng khôi phục danh mục trước.']);
    }

    // Khôi phục món ăn và cập nhật trạng thái thành 'dang_ban'
    $monAn->restore();
    $monAn->update(['trang_thai' => 'dang_ban']);

    // Phát sự kiện cập nhật thực đơn
    broadcast(new ThucDonUpdated($monAn))->toOthers();

    return redirect()->route('mon-an.index')->with('success', 'Khôi phục món ăn thành công .');
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
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new MonAnImport, $request->file('file'));

        return back()->with('success', 'Nhập dữ liệu thành công!');
    }
}
