<?php

namespace App\Http\Controllers;

use App\Models\PhieuXuatKho;
use App\Models\ChiTietPhieuXuatKho;
use App\Models\NhanVien;
use App\Models\NhaCungCap;
use App\Models\NguyenLieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PhieuXuatKhoController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = PhieuXuatKho::query()
                ->with(['nhaCungCap'])
                ->withTrashed();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('trang_thai', function ($row) {
                    return $row->deleted_at
                        ? '<div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Đã xóa</div>'
                        : ($row->trang_thai == 'cho_duyet'
                            ? '<div class="d-flex align-items-center"><i class="fa fa-circle text-warning mr-1"></i> Chờ duyệt</div>'
                            : ($row->trang_thai == 'da_duyet'
                                ? '<div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Đã duyệt</div>'
                                : '<div class="d-flex align-items-center"><i class="fa fa-circle text-secondary mr-1"></i> Đã hủy</div>'));
                })
                ->addColumn('tong_gia_tri', function ($row) {
                    return number_format($row->tong_tien, 0, ',', '.');
                })
                ->addColumn('nha_cung_cap', function ($row) {
                    return $row->nhaCungCap ? $row->nhaCungCap->ten_nha_cung_cap : 'Không có';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex align-items-center">';

                    $html .= '<a href="' . route('phieu-xuat-kho.show', $row->id) . '" class="btn btn-info btn-sm p-2 m-1" title="Xem chi tiết"><i class="fa fa-eye"></i></a>';

                    $html .= '<a href="' . route('phieu-xuat-kho.edit', $row->id) . '" class="btn btn-warning btn-sm p-2 m-1" title="Chỉnh sửa"><i class="fa fa-edit"></i></a>';

                    if ($row->deleted_at) {
                        $html .= '<form action="' . route('phieu-xuat-kho.restore', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field()
                            . '<button type="submit" class="btn btn-success btn-sm p-2 m-1" title="Khôi phục"><i class="fa fa-recycle"></i></button>'
                            . '</form>';
                    } else {
                        $html .= '<form action="' . route('phieu-xuat-kho.destroy', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field()
                            . method_field('DELETE')
                            . '<button type="submit" class="btn btn-danger btn-sm p-2 m-1" title="Xóa"><i class="fa fa-trash"></i></button>'
                            . '</form>';
                    }

                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['trang_thai', 'action'])
                ->make(true);
        }

        return view('admin.phieuxuatkho.list', [
            'route' => route('phieu-xuat-kho.index'),
            'tableId' => 'list-container',
            'searchInputId' => 'searchInput',
        ]);
    }


    public function create()
    {
        $nhanViens = NhanVien::all();
        $nhaCungCaps = NhaCungCap::all();
        $nguyenLieus = NguyenLieu::all();
        return view('phieu_xuat_kho.create', compact('nhanViens', 'nhaCungCaps', 'nguyenLieus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ma_phieu' => 'required|unique:phieu_xuat_khos',
            'ngay_xuat' => 'required|date',
            'loai_phieu' => 'required|in:xuat_bep,xuat_tra_hang,xuat_huy',
            'chi_tiets.*.nguyen_lieu_id' => 'required|exists:nguyen_lieus,id',
            'chi_tiets.*.so_luong' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $phieu = PhieuXuatKho::create($request->only([
                'ma_phieu',
                'ngay_xuat',
                'nhan_vien_id',
                'nguoi_nhan',
                'loai_phieu',
                'nha_cung_cap_id',
                'tong_tien',
                'ghi_chu'
            ]));

            foreach ($request->chi_tiets as $ct) {
                $phieu->chiTiet()->create($ct);
            }

            DB::commit();
            return redirect()->route('phieu-xuat-kho.index')->with('success', 'Tạo phiếu xuất kho thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $phieu = PhieuXuatKho::with('chiTiet.nguyenLieu', 'nhanVien', 'nhaCungCap')->findOrFail($id);
        return view('phieu_xuat_kho.show', compact('phieu'));
    }

    public function edit($id)
    {
        $phieu = PhieuXuatKho::with('chiTiet')->findOrFail($id);
        $nhanViens = NhanVien::all();
        $nhaCungCaps = NhaCungCap::all();
        $nguyenLieus = NguyenLieu::all();
        return view('phieu_xuat_kho.edit', compact('phieu', 'nhanViens', 'nhaCungCaps', 'nguyenLieus'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ngay_xuat' => 'required|date',
            'chi_tiets.*.nguyen_lieu_id' => 'required|exists:nguyen_lieus,id',
            'chi_tiets.*.so_luong' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $phieu = PhieuXuatKho::findOrFail($id);
            $phieu->update($request->only([
                'ngay_xuat',
                'nhan_vien_id',
                'nguoi_nhan',
                'loai_phieu',
                'nha_cung_cap_id',
                'tong_tien',
                'ghi_chu'
            ]));

            // Xóa chi tiết cũ và thêm lại
            $phieu->chiTiet()->delete();
            foreach ($request->chi_tiets as $ct) {
                $phieu->chiTiet()->create($ct);
            }

            DB::commit();
            return redirect()->route('phieu-xuat-kho.index')->with('success', 'Cập nhật phiếu xuất kho thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $phieu = PhieuXuatKho::findOrFail($id);
        $phieu->delete();
        return redirect()->back()->with('success', 'Đã xoá mềm phiếu xuất kho.');
    }

    public function duyet($id)
    {
        $phieu = PhieuXuatKho::findOrFail($id);
        $phieu->trang_thai = 'da_duyet';
        $phieu->save();
        return back()->with('success', 'Phiếu đã được duyệt.');
    }
}
