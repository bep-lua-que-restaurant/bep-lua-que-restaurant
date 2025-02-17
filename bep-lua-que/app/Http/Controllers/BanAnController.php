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
        $query = BanAn::with([
            'phongAn' => function ($q) {
                $q->withTrashed(); // Lấy cả phòng đã bị xóa mềm
            }
        ]);

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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $phongAn = PhongAn::withoutTrashed()->get(); // Chỉ lấy bản ghi chưa bị xóa mềm
        return view('admin.banan.create', compact('phongAn'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBanAnRequest $request)
    {
        //
        $data = $request->validated();
        // dd($data);

        $banAn = BanAn::create($data);

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
        //
        // Lấy dữ liệu hợp lệ từ request
        $validatedData = $request->validated();

        // Cập nhật thông tin bàn ăn
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

    /**
     * Nhập danh sách bàn ăn từ file Excel
     */
}
