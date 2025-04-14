<?php

namespace App\Http\Controllers;

use App\Exports\CaLamExport;
use App\Imports\CaLamImport;
use App\Models\CaLam;
use App\Http\Requests\StoreCaLamRequest;
use App\Http\Requests\UpdateCaLamRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CaLamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request  $request)
    {
        $searchInput = $request->input('searchInput');
        $statusFilter = $request->input('statusFilter');
    
        $query = CaLam::query();
    
        // Apply search filter
        if ($searchInput) {
            $query->where('ten_ca', 'like', '%' . $searchInput . '%');
        }
    
        // Apply status filter
        if ($statusFilter && $statusFilter !== 'Tất cả') {
            if ($statusFilter === 'Đang hoạt động') {
                $query->whereNull('deleted_at');
            } else if ($statusFilter === 'Đã ngừng hoạt động') {
                $query->whereNotNull('deleted_at');
            }
        }
        $data = $query->withTrashed()->latest('id')->paginate(10);
        return view('admin.calam.list', compact('data'));
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
        return view('admin.calam.detail', compact('caLam'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CaLam $caLam)
    {
        return view('admin.calam.edit', compact('caLam'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCaLamRequest $request, CaLam $caLam)
    {
        $data = $request->validated();

        $data['gio_bat_dau'] = Carbon::createFromFormat('H:i', $data['gio_bat_dau'])->format('H:i:s');
        $data['gio_ket_thuc'] = Carbon::createFromFormat('H:i', $data['gio_ket_thuc'])->format('H:i:s');

        // Cập nhật dữ liệu
        $caLam->update($data);

        return back()->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CaLam $caLam)
    {
        $caLam->delete();

        return redirect()->route('ca-lam.index')->with('success', 'Xóa ca làm thành công!');
    }

    public function restore($id)
    {
        $caLam = CaLam::withTrashed()->findOrFail($id);
        $caLam->restore();

        return redirect()->route('ca-lam.index')->with('success', 'Khôi phục ca làm thành công!');
    }

    public function export()
    {
        // Xuất file Excel với tên "CaLam.xlsx"
        return Excel::download(new CaLamExport, 'CaLam.xlsx');
    }
    public function importCaLam(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new CaLamImport, $request->file('file'));

        return back()->with('success', 'Nhập dữ liệu thành công!');
    }
}
