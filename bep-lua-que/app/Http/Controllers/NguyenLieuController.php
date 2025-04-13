<?php

namespace App\Http\Controllers;

use App\Exports\NguyenLieuExport;
use App\Models\NguyenLieu;
use App\Models\LoaiNguyenLieu;
use App\Http\Requests\StoreNguyenLieuRequest;
use App\Http\Requests\UpdateNguyenLieuRequest;
use App\Imports\NguyenLieuImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PhieuXuatKho;
use App\Models\ChiTietPhieuXuatKho;
use App\Models\HoaDonBan;
use App\Models\HoaDon;
use App\Models\ChiTietHoaDon;
use App\Models\CongThucMonAn;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class NguyenLieuController extends Controller
{
    /**
     * Hiển thị danh sách nguyên liệu.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = NguyenLieu::query()
                ->with(['loaiNguyenLieu']) // Giả sử có quan hệ loaiNguyenLieu()
                ->withTrashed();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('trang_thai', function ($row) {
                    return $row->deleted_at
                        ? '<div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Đã xóa</div>'
                        : '<div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Đang sử dụng</div>';
                })
                ->addColumn('loai_nguyen_lieu', function ($row) {
                    return $row->loaiNguyenLieu ? $row->loaiNguyenLieu->ten_loai : 'Không xác định';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex align-items-center">';

                    // Nút xem chi tiết
                    $html .= '<a href="' . route('nguyen-lieu.show', $row->id) . '" class="btn btn-info btn-sm p-2 m-2" title="Xem chi tiết"><i class="fa fa-eye"></i></a>';

                    // Nút chỉnh sửa
                    // $html .= '<a href="' . route('nguyen-lieu.edit', $row->id) . '" class="btn btn-warning btn-sm p-2 m-2" title="Chỉnh sửa"><i class="fa fa-edit"></i></a>';

                    // Nút xóa hoặc khôi phục tùy theo trạng thái soft delete
                    if ($row->deleted_at) {
                        $html .= '<form action="' . route('nguyen-lieu.restore', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field()
                            . '<button type="submit" class="btn btn-success btn-sm p-2 m-2" title="Khôi phục"><i class="fa fa-recycle"></i></button>'
                            . '</form>';
                    } else {
                        $html .= '<form action="' . route('nguyen-lieu.destroy', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field()
                            . method_field('DELETE')
                            . '<button type="submit" class="btn btn-danger btn-sm p-2 m-2" title="Xóa"><i class="fa fa-trash"></i></button>'
                            . '</form>';
                    }

                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['trang_thai', 'action'])
                ->make(true);
        }

        return view('admin.nguyenlieu.list', [
            'route' => route('nguyen-lieu.index'),
            'tableId' => 'list-container',
            'searchInputId' => 'searchInput',
        ]);
    }

    public function kiemTraTonKhoTrongNgay(Request $request)
    {
        $ngay = $request->input('ngay') ? Carbon::parse($request->input('ngay')) : Carbon::today();
        $loaiId = $request->input('loai_nguyen_lieu_id');
        $soNgay = 7;
        $ngayBatDau = $ngay->copy()->subDays($soNgay - 1);

        // Lọc nguyên liệu
        $nguyenLieus = NguyenLieu::when($loaiId, function ($query) use ($loaiId) {
            $query->where('loai_nguyen_lieu_id', $loaiId);
        })->get();

        // Tính tồn kho và sử dụng trong ngày
        $duLieuTonKho = $nguyenLieus->map(function ($nl) use ($ngay) {
            $soLuongXuat = ChiTietPhieuXuatKho::whereHas('phieuXuatKho', function ($query) use ($ngay) {
                $query->whereDate('ngay_xuat', $ngay)->where('trang_thai', 'da_duyet');
            })->where('nguyen_lieu_id', $nl->id)->sum(DB::raw('so_luong * he_so_quy_doi'));

            $hoaDonIds = HoaDonBan::where('trang_thai', 'da_thanh_toan')->whereDate('created_at', $ngay)->pluck('hoa_don_id');

            $soLuongDung = ChiTietHoaDon::whereIn('hoa_don_id', $hoaDonIds)->get()->sum(function ($cthd) use ($nl) {
                $ct = CongThucMonAn::where('mon_an_id', $cthd->mon_an_id)->where('nguyen_lieu_id', $nl->id)->first();
                return $ct ? $cthd->so_luong * $ct->so_luong : 0;
            });

            return [
                'id'               => $nl->id,
                'nguyen_lieu'      => $nl->ten_nguyen_lieu,
                'don_vi'           => $nl->don_vi_ton,
                'ton_kho_hien_tai' => $nl->so_luong_ton,
                'da_xuat'          => $soLuongXuat,
                'da_dung'          => $soLuongDung,
                'chenh_lech'       => $soLuongXuat - $soLuongDung,
            ];
        });

        // Tính định mức trung bình 7 ngày
        $duLieuDinhMuc = $nguyenLieus->map(function ($nl) use ($ngayBatDau, $ngay, $soNgay) {
            $hoaDonIds = HoaDonBan::where('trang_thai', 'da_thanh_toan')
                ->whereBetween('created_at', [$ngayBatDau, $ngay])
                ->pluck('hoa_don_id');

            $tongSoLuongDung = ChiTietHoaDon::whereIn('hoa_don_id', $hoaDonIds)->get()->sum(function ($cthd) use ($nl) {
                $ct = CongThucMonAn::where('mon_an_id', $cthd->mon_an_id)->where('nguyen_lieu_id', $nl->id)->first();
                return $ct ? $cthd->so_luong * $ct->so_luong : 0;
            });

            return [
                'id'                  => $nl->id,
                'nguyen_lieu'         => $nl->ten_nguyen_lieu,
                'trung_binh_su_dung'  => round($tongSoLuongDung / $soNgay, 2),
                'ton_kho'             => $nl->so_luong_ton,
            ];
        });

        // Cảnh báo nguyên liệu tồn thấp hơn trung bình
        $duLieuCanhBao = collect();
        foreach ($duLieuDinhMuc as $dm) {
            $ton = $duLieuTonKho->firstWhere('id', $dm['id']);
            if ($ton && $ton['ton_kho_hien_tai'] < $dm['trung_binh_su_dung']) {
                $duLieuCanhBao->push([
                    'nguyen_lieu'         => $dm['nguyen_lieu'],
                    'ton_kho'             => $ton['ton_kho_hien_tai'],
                    'trung_binh_su_dung'  => $dm['trung_binh_su_dung'],
                    'don_vi'              => $ton['don_vi'],
                ]);
            }
        }

        $dsLoai = LoaiNguyenLieu::all();

        return view('admin.nguyenlieu.kiemtratonkho', compact(
            'duLieuTonKho',
            'duLieuDinhMuc',
            'duLieuCanhBao',
            'ngay',
            'loaiId',
            'dsLoai',
            'soNgay'
        ));
    }





    /**
     * Hiển thị thông tin chi tiết nguyên liệu.
     */

    public function show($id)
    {
        try {
            $nguyenLieu = NguyenLieu::withTrashed()
                ->with(['loaiNguyenLieu' => function ($query) {
                    $query->withTrashed();
                }])
                ->findOrFail($id);

            return view('admin.nguyenlieu.detail', compact('nguyenLieu'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Nguyên liệu không tồn tại hoặc đã bị xóa.');
        }
    }



    /**
     * Xóa nguyên liệu (soft delete).
     */
    public function destroy(NguyenLieu $nguyenLieu)
    {
        try {
            $nguyenLieu->delete();
            return redirect()->route('nguyen-lieu.index')->with('success', 'Xóa nguyên liệu thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xóa nguyên liệu.');
        }
    }


    /**
     * Khôi phục nguyên liệu đã xóa.
     */
    public function restore($id)
    {
        try {
            $nguyenLieu = NguyenLieu::withTrashed()->findOrFail($id);
            if ($nguyenLieu->trashed()) {
                $nguyenLieu->restore();
                return redirect()->route('nguyen-lieu.index')->with('success', 'Khôi phục nguyên liệu thành công!');
            } else {
                return redirect()->back()->with('info', 'Nguyên liệu này đang hoạt động và không cần khôi phục.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Không thể khôi phục nguyên liệu.');
        }
    }

    public function export()
    {
        return Excel::download(new NguyenLieuExport, 'nguyen_lieu.xlsx');
    }

    public function importNguyenLieu(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new NguyenLieuImport, $request->file('file'));

        return back()->with('success', 'Import nguyên liệu thành công!');
    }
}
