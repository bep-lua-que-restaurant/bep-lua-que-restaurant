<?php

namespace App\Http\Controllers;

use App\Models\DanhMucMonAn;
use App\Http\Requests\StoreDanhMucMonAnRequest;
use App\Http\Requests\UpdateDanhMucMonAnRequest;
use Illuminate\Http\Request;
use App\Exports\DanhMucMonAnExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DanhMucMonAnImport;
use App\Models\MonAn;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DanhMucMonAnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DanhMucMonAn::query()->withTrashed();
    
            return DataTables::of($query)
                ->addIndexColumn() // ✅ Tạo cột STT
                ->addColumn('trang_thai', function ($row) {
                    return $row->deleted_at
                        ? '<div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Đã ngừng kinh doanh</div>'
                        : '<div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Đang kinh doanh</div>';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex align-items-center">';
    
                    // Chỉ hiển thị nút sửa nếu chưa bị xóa mềm
                    if (!$row->deleted_at) {
                        $html .= '<button type="button" class="btn btn-warning btn-sm p-2 m-2 btn-edit" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa fa-edit"></i></button>';
                    }
    
                    // Nút khôi phục hoặc xóa
                    if ($row->deleted_at) {
                        $html .= '<form action="' . route('danh-muc-mon-an.restore', $row->id) . '" method="POST" style="display:inline;">' . csrf_field()
                            . '<button type="submit" class="btn btn-success btn-sm p-2 m-2" title="Khôi phục"><i class="fa fa-recycle"></i></button></form>';
                    } else {
                        $html .= '<form action="' . route('danh-muc-mon-an.destroy', $row->id) . '" method="POST" style="display:inline;">' . csrf_field() . method_field('DELETE')
                            . '<button type="submit" class="btn btn-danger btn-sm p-2 m-2" title="Xóa"><i class="fa fa-trash"></i></button></form>';
                    }
    
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['trang_thai', 'action']) // ✅ Giữ nguyên HTML
                ->make(true);
        }
    
        return view('admin.danhmuc.list', [
            'route' => route('danh-muc-mon-an.index'),
            'tableId' => 'list-container',
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */


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

        $danhMuc = DanhMucMonAn::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Tạo danh mục mới thành công!',
            'data' => $danhMuc
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(DanhMucMonAn $danhMucMonAn)
    {
        return response()->json($danhMucMonAn);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDanhMucMonAnRequest $request, DanhMucMonAn $danhMucMonAn)
    {
        $data = $request->validated();

        if ($request->hasFile('hinh_anh')) {
            if ($danhMucMonAn->hinh_anh) {
                Storage::disk('public')->delete($danhMucMonAn->hinh_anh);
            }

            $data['hinh_anh'] = $request->file('hinh_anh')->store('DanhMucImg', 'public');
        }

        $danhMucMonAn->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật danh mục thành công!',
            'data' => $danhMucMonAn
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, DanhMucMonAn $danhMucMonAn)
    {
        try {
            // Kiểm tra xem danh mục có món ăn nào liên quan đến hóa đơn bàn đã thanh toán không
            $hasPaidOrder = MonAn::where('danh_muc_mon_an_id', $danhMucMonAn->id)
                ->join('chi_tiet_hoa_dons', 'mon_ans.id', '=', 'chi_tiet_hoa_dons.mon_an_id')
                ->join('hoa_dons', 'chi_tiet_hoa_dons.hoa_don_id', '=', 'hoa_dons.id')
                ->join('hoa_don_bans', 'hoa_dons.id', '=', 'hoa_don_bans.hoa_don_id')
                ->where('hoa_don_bans.trang_thai', 'da_thanh_toan')
                ->exists();
    
            if ($hasPaidOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Danh mục này có món ăn đang được sử dụng, không thể xóa.'
                ], 422);
            }
    
            // Xóa mềm danh mục
            $danhMucMonAn->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Xóa danh mục thành công.'
            ]);
        } catch (\Exception $e) {
            // Ghi log lỗi để debug
            Log::error('Lỗi khi xóa danh mục món ăn: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi xóa danh mục. Vui lòng thử lại.'
            ], 500);
        }
    }

    public function restore($id)
    {
        $danhMucMonAn = DanhMucMonAn::withTrashed()->findOrFail($id);
        $danhMucMonAn->restore();

        return redirect()->route('danh-muc-mon-an.index');
    }

    public function export()
    {
        // Xuất file Excel với tên "DanhMucMonAn.xlsx"
        return Excel::download(new DanhMucMonAnExport, 'DanhMucMonAn.xlsx');
    }

    public function importDanhMucMonAn(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new DanhMucMonAnImport, $request->file('file'));

        return back()->with('success', 'Nhập dữ liệu thành công!');
    }
}
