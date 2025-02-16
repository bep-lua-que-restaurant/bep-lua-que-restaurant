<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Http\Requests\StoreHoaDonRequest;
use App\Http\Requests\UpdateHoaDonRequest;
use Illuminate\Http\Request;

class HoaDonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HoaDon::query();
    
        if ($request->has('search') && $request->search != '') {
            $query->where('ma_hoa_don', 'like', '%' . $request->search . '%')
                  ->orWhere('khach_hang_id', 'like', '%' . $request->search . '%');
        }

        $hoa_don = $query->latest('id')->paginate(10);
    
        // Nếu là Ajax request, trả về HTML của bảng luôn
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.hoadon.index', compact('hoa_don'))->render(),
            ]);
        }
    
        return view('admin.hoadon.index', compact('hoa_don'));
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
    public function store(StoreHoaDonRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $hoaDon = HoaDon::with(['chiTietHoaDons.monAn','banAns'])->findOrFail($id);
   
    return view('admin.hoadon.show', compact('hoaDon'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HoaDon $hoaDon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHoaDonRequest $request, HoaDon $hoaDon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HoaDon $hoaDon)
    {
        //
    }
}
