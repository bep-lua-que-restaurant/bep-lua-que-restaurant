<?php

namespace App\Http\Controllers;
use App\Models\LichLamViec;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LichLamViecExport;


class LichLamViecController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $lichLamViec = LichLamViec::with('nhanVien')
            ->when($search, function ($query, $search) {
                return $query->whereHas('nhanVien', function ($q) use ($search) {
                    $q->where('ten', 'LIKE', "%$search%");
                });
            })
            ->orderBy('ngay', 'asc')
            ->get();
        return view('admin.lichlamviec.index', compact('lichLamViec', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nhan_vien_id' => 'required',
            'ca' => 'required',
            'ngay' => 'required|date',
        ]);
        LichLamViec::create($request->all());
        return redirect()->back()->with('success', 'Thêm lịch làm việc thành công!');
    }

    public function update(Request $request, LichLamViec $lich)
    {
        $request->validate([
            'ca' => 'required',
            'ngay' => 'required|date',
        ]);
        $lich->update($request->all());
        return redirect()->back()->with('success', 'Cập nhật lịch làm việc thành công!');
    }

    public function destroy(LichLamViec $lich)
    {
        $lich->delete();
        return redirect()->back()->with('success', 'Xóa lịch làm việc thành công!');
    }

    public function export()
    {
        // return Excel::download(new LichLamViecExport, 'lich_lam_viec.xlsx');
    }
}