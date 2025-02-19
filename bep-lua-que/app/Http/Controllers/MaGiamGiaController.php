<?php

namespace App\Http\Controllers;

use App\Models\MaGiamGia;
use App\Http\Requests\StoreMaGiamGiaRequest;
use App\Http\Requests\UpdateMaGiamGiaRequest;
use Illuminate\Http\Request;
class MaGiamGiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request  $request)
    {
        $query = MaGiamGia::query();

        if ($request->has('ten') && $request->ten != '') {
            $query->where('code', 'like', '%' . $request->ten . '%');
        }

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
                'html' => view('admin.magiamgia.body-list', compact('data'))->render(),
            ]);
        }

        return view('admin.danhmuc.list', [
            'data' => $data,
            'route' => route('ma-giam-gia.index'), // URL route cho AJAX
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaGiamGiaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MaGiamGia $maGiamGia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaGiamGia $maGiamGia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaGiamGiaRequest $request, MaGiamGia $maGiamGia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaGiamGia $maGiamGia)
    {
        //
    }
}
