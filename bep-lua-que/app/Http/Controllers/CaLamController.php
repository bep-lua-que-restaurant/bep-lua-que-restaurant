<?php

namespace App\Http\Controllers;

use App\Models\CaLam;
use App\Http\Requests\StoreCaLamRequest;
use App\Http\Requests\UpdateCaLamRequest;
use Illuminate\Http\Request;

class CaLamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request  $request)
    {
        $query = CaLam::query();

        if ($request->has('ten_ca') && $request->ten_ca != '') {
            $query->where('ten_ca', 'like', '%' . $request->ten_ca . '%');
        }

        $data = $query->withTrashed()->latest('id')->paginate(15);

        // Xử lý trả về khi yêu cầu là Ajax
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.calam.body-list', compact('data'))->render(),
            ]);
        }

        return view('admin.calam.list', [
            'data' => $data,
            'route' => route('ca-lam.index'), // URL route cho AJAX
            'tableId' => 'list-container', // ID của bảng
            'searchInputId' => 'search-name', // ID của ô tìm kiếm
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.calam.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCaLamRequest $request)
    {
        //
        $data = $request->validated();

        CaLam::create($data);

        return redirect()->route('ca-lam.index')->with('success', 'Thêm ca làm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CaLam $caLam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CaLam $caLam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCaLamRequest $request, CaLam $caLam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CaLam $caLam)
    {
        //
    }
}
