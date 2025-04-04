<?php

namespace App\Http\Controllers;

use App\Events\BanAnUpdated;
use App\Models\BanAn;
use App\Http\Requests\StoreBanAnRequest;
use App\Http\Requests\UpdateBanAnRequest;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BanAnExport;
use App\Imports\BanAnImport;
use App\Models\PhongAn;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\HeadingRowImport;

class BanAnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BanAn::query(); // Xóa with('phongAn') vì không cần nữa

        // Lọc theo tên
        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten_ban', 'like', '%' . $request->ten . '%');
        }

        // Lọc theo trạng thái kinh doanh
        if ($request->has('statusFilter') && $request->statusFilter != '') {
            if ($request->statusFilter == 'Đang kinh doanh') {
                $query->whereNull('deleted_at');
            } elseif ($request->statusFilter == 'Ngừng kinh doanh') {
                $query->whereNotNull('deleted_at');
            }
        }

        $data = $query->withTrashed()->latest('id')->paginate(15);

        // Xử lý trả về khi yêu cầu là Ajax
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.banan.body-list', compact('data'))->render(),
            ]);
        }

        return view('admin.banan.index', [
            'data' => $data,
            'route' => route('ban-an.index'),
            'tableId' => 'list-container',
            'searchInputId' => 'search-name',
        ]);
    }


    public function getSoLuongBan(Request $request)
    {
        $phongId = $request->vi_tri;

        if (!$phongId) {
            return response()->json(['error' => 'Vui lòng chọn vị trí phòng.'], 400);
        }

        // Đếm số lượng bàn theo loại trong phòng
        $soLuongBan = BanAn::where('vi_tri', $phongId)
            ->selectRaw('so_ghe, COUNT(*) as count')
            ->groupBy('so_ghe')
            ->pluck('count', 'so_ghe');

        return response()->json([
            'success' => true,
            'soLuongBan' => $soLuongBan
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // một phòng có tối đa bàn loại 4 ghế là 6 , tối đa bàn loại 8 ghế là 4, tối đa bàn loại 10 ghế là 2
        $phongAn = PhongAn::withoutTrashed()->get(); // Chỉ lấy bản ghi chưa bị xóa mềm
        return view('admin.banan.create', compact('phongAn'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBanAnRequest $request)
    {
        $data = $request->validated();

        // Nếu không có 'so_ghe', đặt giá trị mặc định là 4
        $data['so_ghe'] = $data['so_ghe'] ?? 4;

        // Debug (nếu cần)
        // dd($data);

        // Tạo bản ghi mới
        $banAn = BanAn::create($data);

        // Gửi sự kiện realtime
        broadcast(new BanAnUpdated($banAn))->toOthers();

        return redirect()->route('ban-an.index')->with('success', 'Thêm bàn ăn thành công!');
    }




    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Lấy thông tin Bàn ăn, kể cả khi bị xóa mềm
        $banAn = BanAn::withTrashed()->findOrFail($id);

        // Lấy thông tin Phòng ăn từ `vi_tri` (chứa ID của Phòng ăn)
        $phongAn = PhongAn::withTrashed()->find($banAn->vi_tri);

        return view('admin.banan.detail', compact('banAn', 'phongAn'));
    }


    /**
     * Show the form for editing the specified resource.
     */ public function edit(BanAn $banAn)
    {
        // Lấy danh sách Phòng ăn chưa bị xóa mềm
        $phongAns = PhongAn::withoutTrashed()->get();

        return view('admin.banan.edit', compact('banAn', 'phongAns'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBanAnRequest $request, BanAn $banAn)
    {
        $validatedData = $request->validated();

        // Debug để kiểm tra dữ liệu nhận được
        // dd($validatedData);

        $banAn->update($validatedData);

        broadcast(new BanAnUpdated($banAn))->toOthers();

        return redirect()->route('ban-an.index')->with('success', 'Cập nhật bàn ăn thành công!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BanAn $banAn)
    {
        $banAn->delete(); // Xóa mềm bàn ăn

        broadcast(new BanAnUpdated($banAn))->toOthers();

        return redirect()->route('ban-an.index')->with('success', 'Bàn ăn đã được ngừng sử dụng!');
    }

    public function restore($id)
    {
        $banAn = BanAn::withTrashed()->findOrFail($id);

        if ($banAn->deleted_at) {
            $banAn->restore(); // Khôi phục bàn ăn
            broadcast(new BanAnUpdated($banAn))->toOthers();
            return redirect()->route('ban-an.index')->with('success', 'Bàn ăn đã được khôi phục!');
        }


        return redirect()->route('ban-an.index')->with('error', 'Bàn ăn này chưa bị xóa!');
    }

    public function export()
    {
        return Excel::download(new BanAnExport, 'DanhSachBanAn.xlsx');
    }

    public function importBanAn(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new BanAnImport, $request->file('file'));

        return back()->with('success', 'Nhập dữ liệu thành công!');
    }

    public function them()
    {
        return view('admin.banan.themNhanh');
    }

    public function storeQuick(Request $request)
    {
        $request->validate([
            'so_luong' => 'required|integer|min:1',
            'prefix' => 'required|string|max:50',
        ]);

        $soLuong = $request->input('so_luong');
        $prefix = $request->input('prefix');
        $maxCount = BanAn::count();

        for ($i = 1; $i <= $soLuong; $i++) {
            $number = $maxCount + $i;
            $banAn = BanAn::create([
                'ten_ban' => $prefix . ' ' . $number, // Có khoảng trống như yêu cầu
                'so_ghe' => 4,
                'trang_thai' => 'trống',
                // 'ma' => 'MA' . str_pad($number, 4, '0', STR_PAD_LEFT), // Nếu cần cột ma
            ]);

            // Gửi sự kiện real-time cho từng bàn được tạo
            broadcast(new BanAnUpdated($banAn))->toOthers();
        }

        return redirect()->route('ban-an.index')->with('success', "Đã thêm $soLuong bàn ăn thành công!");
    }
}
