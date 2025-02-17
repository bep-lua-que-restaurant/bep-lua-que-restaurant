<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBanAnRequest;
use App\Http\Requests\StorePhongAnRequest;
use App\Http\Requests\UpdatePhongAnRequest;
use App\Models\PhongAn;
use Illuminate\Http\Request;

class PhongAnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PhongAn::query();

        // Lọc theo tên
        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten_phong_an', 'like', '%' . $request->ten . '%');
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
                'html' => view('admin.phongan.body-list', compact('data'))->render(),
            ]);
        }

        return view('admin.phongan.index', [
            'data' => $data,
            'route' => route('phong-an.index'), // URL route cho AJAX
            'tableId' => 'list-container', // ID của bảng
            'searchInputId' => 'search-name', // ID của ô tìm kiếm
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.phongan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhongAnRequest $request)
    {
        //
        $data = $request->validated();


        PhongAn::create($data);

        return redirect()->route('phong-an.index')->with('success', 'Thêm phòng ăn thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $phongAn = PhongAn::withTrashed()->findOrFail($id);

        return view('admin.phongan.detail', compact('phongAn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PhongAn $phongAn)
    {
        //
        return view('admin.phongan.edit', compact('phongAn'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhongAnRequest $request, PhongAn $phongAn)
    {
        //
        $validatedData = $request->validated();

        // Cập nhật thông tin phòng ăn
        $phongAn->update($validatedData);

        return redirect()->route('phong-an.index')->with('success', 'Cập nhật phòng ăn thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(phongAn $phongAn)
    {
        //
        $phongAn->delete(); // Xóa mềm phòng ăn

        return redirect()->route('phong-an.index')->with('success', 'Phòng ăn đã được ngừng sử dụng!');
    }

    public function restore($id)
    {
        $phongAn = PhongAn::withTrashed()->findOrFail($id);

        if ($phongAn->deleted_at) {
            $phongAn->restore(); // Khôi phục bàn ăn
            return redirect()->route('phong-an.index')->with('success', 'Phòng ăn đã được khôi phục!');
        }

        return redirect()->route('phong-an.index')->with('error', 'Phòng ăn này chưa bị xóa!');
    }
}
