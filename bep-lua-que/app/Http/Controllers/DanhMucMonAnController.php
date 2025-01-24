<?php

namespace App\Http\Controllers;

use App\Models\DanhMucMonAn;
use App\Http\Requests\StoreDanhMucMonAnRequest;
use App\Http\Requests\UpdateDanhMucMonAnRequest;
use Illuminate\Http\Request;

class DanhMucMonAnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request  $request)
    {
        $query = DanhMucMonAn::query();

        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten', 'like', '%' . $request->ten . '%');
        }

        $data = $query->withTrashed()->latest('id')->paginate(15);

        // Xử lý trả về khi yêu cầu là Ajax
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.danhmuc.body-list', compact('data'))->render(),
            ]);
        }

        return view('admin.danhmuc.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.danhmuc.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDanhMucMonAnRequest $request)
    {
        //
        $data = $request->validated();

        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('DanhMucImg', 'public');
        }

        DanhMucMonAn::create($data);

        return redirect()->route('danh-muc-mon-an.index')->with('success', 'Thêm danh mục thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DanhMucMonAn $danhMucMonAn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DanhMucMonAn $danhMucMonAn)
    {
        return response()->json($danhMucMonAn);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDanhMucMonAnRequest $request, DanhMucMonAn $danhMucMonAn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DanhMucMonAn $danhMucMonAn)
    {
        //
    }
}
