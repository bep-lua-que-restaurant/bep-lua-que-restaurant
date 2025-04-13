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
    public function index()
    {
        return view('admin.banan.index'); // chỉ render giao diện
    }

    public function fetchData(Request $request)
    {
        $query = BanAn::query();

        if ($request->filled('ten')) {
            $query->where('ten_ban', 'like', '%' . $request->ten . '%');
        }

        if ($request->filled('statusFilter')) {
            if ($request->statusFilter == 'Đang kinh doanh') {
                $query->whereNull('deleted_at');
            } elseif ($request->statusFilter == 'Ngừng kinh doanh') {
                $query->whereNotNull('deleted_at');
            }
        }

        // $data = $query->withTrashed()->latest('id')->paginate(10);
        $data = $query->withTrashed()->latest('id')->paginate(10);

        return response()->json([
            'data' => $data
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
        $banAn = BanAn::withTrashed()->findOrFail($id);

        // Trả JSON cho AJAX
        return response()->json([
            'id' => $banAn->id,
            'ten_ban' => $banAn->ten_ban,
            'so_ghe' => $banAn->so_ghe,
            'mo_ta' => $banAn->mo_ta,
            'trang_thai' => $banAn->deleted_at ? 'Ngừng sử dụng' : 'Đang sử dụng',
            'deleted_at' => $banAn->deleted_at,
        ]);
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
        if ($banAn->trang_thai !== 'trong') {
            return response()->json(['message' => 'Chỉ có thể ngừng sử dụng bàn khi bàn đang trống!'], 422);
        }

        $banAn->delete();

        broadcast(new BanAnUpdated($banAn))->toOthers();

        return response()->json(['message' => 'Bàn ăn đã được ngừng sử dụng!']);
    }

    public function restore($id)
    {
        $banAn = BanAn::withTrashed()->findOrFail($id);

        if (!$banAn->deleted_at) {
            return response()->json(['message' => 'Bàn ăn này chưa bị xóa!'], 422);
        }

        $banAn->restore();
        broadcast(new BanAnUpdated($banAn))->toOthers();

        return response()->json(['message' => 'Bàn ăn đã được khôi phục!']);
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

    // public function storeQuick(Request $request)
    // {
    //     $request->validate([
    //         'so_luong' => 'required|integer|min:1',
    //         'prefix' => 'required|string|max:50',
    //     ]);

    //     $soLuong = $request->input('so_luong');
    //     $prefix = $request->input('prefix');
    //     $maxCount = BanAn::count();

    //     for ($i = 1; $i <= $soLuong; $i++) {
    //         $number = $maxCount + $i;
    //         $banAn = BanAn::create([
    //             'ten_ban' => $prefix . ' ' . $number, // Có khoảng trống như yêu cầu
    //             'so_ghe' => 4,
    //             'trang_thai' => 'trống',
    //             // 'ma' => 'MA' . str_pad($number, 4, '0', STR_PAD_LEFT), // Nếu cần cột ma
    //         ]);

    //         // Gửi sự kiện real-time cho từng bàn được tạo
    //         broadcast(new BanAnUpdated($banAn))->toOthers();
    //     }

    //     return redirect()->route('ban-an.index')->with('success', "Đã thêm $soLuong bàn ăn thành công!");
    // }

    public function storeQuick(Request $request)
    {
        $request->validate([
            'so_luong' => 'required|integer|min:1',
            'prefix' => 'required|string|max:50',
        ]);

        $soLuong = $request->input('so_luong');
        $prefix = $request->input('prefix');
        $maxCount = BanAn::withTrashed()->count();

        $createdBanAn = [];

        for ($i = 1; $i <= $soLuong; $i++) {
            $number = $maxCount + $i;
            $banAn = BanAn::create([
                'ten_ban' => $prefix . ' ' . $number,
                'so_ghe' => 4,
                'trang_thai' => 'trong', // dùng đúng với JS
            ]);
            $createdBanAn[] = $banAn;

            broadcast(new BanAnUpdated($banAn))->toOthers();
        }

        // Trả về toàn bộ danh sách bàn ăn để render lại bảng
        $allBanAn = BanAn::withTrashed()->orderBy('id', 'desc')->get();

        return response()->json([
            'message' => "Đã thêm $soLuong bàn ăn thành công!",
            'data' => $allBanAn
        ]);
    }
}
