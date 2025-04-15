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
use App\Models\ChiTietPhieuNhapKho;
use App\Models\CongThucMonAn;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public function tonKhoXuatDung(Request $request)
    {
        $ngay = $request->input('ngay') ? Carbon::parse($request->input('ngay')) : Carbon::today();
        $loaiId = $request->input('loai_nguyen_lieu_id');

        // 1. Lấy ID nguyên liệu theo loại (có thể đã bị xóa mềm)
        $nguyenLieuIdsTheoLoai = NguyenLieu::withTrashed()
            ->when($loaiId, fn($q) => $q->where('loai_nguyen_lieu_id', $loaiId))
            ->pluck('id');

        // 2. Lấy thêm nguyên liệu đã bị xoá nhưng vẫn có dữ liệu xuất trong ngày VÀ cùng loại
        $nguyenLieuXuatTrongNgayIds = DB::table('chi_tiet_phieu_xuat_khos')
            ->join('phieu_xuat_khos', 'chi_tiet_phieu_xuat_khos.phieu_xuat_kho_id', '=', 'phieu_xuat_khos.id')
            ->join('nguyen_lieus', 'chi_tiet_phieu_xuat_khos.nguyen_lieu_id', '=', 'nguyen_lieus.id')
            ->where('phieu_xuat_khos.trang_thai', 'da_duyet')
            ->whereDate('phieu_xuat_khos.ngay_xuat', $ngay)
            ->when($loaiId, fn($q) => $q->where('nguyen_lieus.loai_nguyen_lieu_id', $loaiId))
            ->pluck('chi_tiet_phieu_xuat_khos.nguyen_lieu_id')
            ->unique();

        // 3. Kết hợp tất cả nguyên liệu cần hiển thị
        $nguyenLieuIds = $nguyenLieuIdsTheoLoai
            ->merge($nguyenLieuXuatTrongNgayIds)
            ->unique();

        $nguyenLieus = NguyenLieu::withTrashed()->whereIn('id', $nguyenLieuIds)->get();

        // 4. Phần tính toán dữ liệu như bạn đã có
        $duLieuTonKho = $nguyenLieus->map(function ($nl) use ($ngay) {
            $nguyenLieuId = $nl->id;

            $tinhSoLuongTheoLoai = function ($loaiPhieu = null) use ($nguyenLieuId, $ngay) {
                $query = DB::table('chi_tiet_phieu_xuat_khos')
                    ->join('phieu_xuat_khos', 'chi_tiet_phieu_xuat_khos.phieu_xuat_kho_id', '=', 'phieu_xuat_khos.id')
                    ->where('phieu_xuat_khos.trang_thai', 'da_duyet')
                    ->whereDate('phieu_xuat_khos.ngay_xuat', $ngay)
                    ->where('chi_tiet_phieu_xuat_khos.nguyen_lieu_id', $nguyenLieuId);

                if ($loaiPhieu) {
                    $query->where('phieu_xuat_khos.loai_phieu', $loaiPhieu);
                }

                return $query->sum(DB::raw('so_luong * COALESCE(he_so_quy_doi, 1)'));
            };

            $soLuongXuatBep = $tinhSoLuongTheoLoai('xuat_bep');
            $soLuongTraHang = $tinhSoLuongTheoLoai('xuat_tra_hang');
            $soLuongXuatHuy = $tinhSoLuongTheoLoai('xuat_huy');
            $tongSoLuongXuat = $tinhSoLuongTheoLoai();

            $hoaDonIds = HoaDonBan::where('trang_thai', 'da_thanh_toan')
                ->whereDate('created_at', $ngay)
                ->pluck('hoa_don_id');

            $soLuongDung = ChiTietHoaDon::whereIn('hoa_don_id', $hoaDonIds)->get()->sum(function ($cthd) use ($nguyenLieuId) {
                $ct = CongThucMonAn::where('mon_an_id', $cthd->mon_an_id)
                    ->where('nguyen_lieu_id', $nguyenLieuId)
                    ->first();
                return $ct ? $cthd->so_luong * $ct->so_luong : 0;
            });

            return [
                'id' => $nguyenLieuId,
                'nguyen_lieu' => $nl->ten_nguyen_lieu,
                'don_vi' => $nl->don_vi_ton,
                'ton_kho_hien_tai' => $nl->so_luong_ton,
                'tong_da_xuat' => $tongSoLuongXuat,
                'xuat_bep' => $soLuongXuatBep,
                'xuat_tra_hang' => $soLuongTraHang,
                'xuat_huy' => $soLuongXuatHuy,
                'da_dung' => $soLuongDung,
                'chenh_lech_xuat_dung' => $soLuongXuatBep - $soLuongDung,
                'can_nhap_them' => $soLuongDung > $nl->so_luong_ton ? 'Cần nhập thêm' : '',
                'da_ngung_su_dung' => $nl->trashed(),
            ];
        });

        return response()->json($duLieuTonKho);
    }




    public function viewTonKhoXuatDung()
    {
        $loaiNguyenLieus = LoaiNguyenLieu::all();
        return view('admin.nguyenlieu.kiemtratonkho', compact('loaiNguyenLieus'));
    }


    public function tonKhoDinhMuc(Request $request)
    {
        $ngay = $request->input('ngay') ? Carbon::parse($request->input('ngay')) : Carbon::today();
        $loaiId = $request->input('loai_nguyen_lieu_id');
        $soNgay = 7;
        $ngayBatDau = $ngay->copy()->subDays($soNgay - 1);

        $nguyenLieus = NguyenLieu::when($loaiId, fn($q) => $q->where('loai_nguyen_lieu_id', $loaiId))->get();

        $duLieu = $nguyenLieus->map(function ($nl) use ($ngayBatDau, $ngay, $soNgay) {
            $hoaDonIds = HoaDonBan::where('trang_thai', 'da_thanh_toan')
                ->whereBetween('created_at', [$ngayBatDau, $ngay])
                ->pluck('hoa_don_id');

            $tong = ChiTietHoaDon::whereIn('hoa_don_id', $hoaDonIds)->get()->sum(function ($cthd) use ($nl) {
                $ct = CongThucMonAn::where('mon_an_id', $cthd->mon_an_id)->where('nguyen_lieu_id', $nl->id)->first();
                return $ct ? $cthd->so_luong * $ct->so_luong : 0;
            });

            return [
                'id' => $nl->id,
                'nguyen_lieu' => $nl->ten_nguyen_lieu,
                'trung_binh_su_dung' => round($tong / $soNgay, 2),
                'ton_kho' => $nl->so_luong_ton,
                'don_vi' => $nl->don_vi_ton,
            ];
        });

        return response()->json($duLieu);
    }



    public function HanSuDung()
    {
        try {
            $today = Carbon::now();

            $hanSuDungList = ChiTietPhieuNhapKho::with('nguyenLieu')
                ->where('so_luong_nhap', '>', 0)
                ->whereNotNull('han_su_dung')
                ->get()
                ->groupBy('nguyen_lieu_id');

            $results = [];

            foreach ($hanSuDungList as $nguyenLieuId => $items) {
                $firstItem = $items->first();

                if (!$firstItem || !$firstItem->nguyenLieu) {
                    continue;
                }

                $nguyenLieu = $firstItem->nguyenLieu;
                $tenNguyenLieu = $nguyenLieu->ten_nguyen_lieu;
                $soLuongTon = $nguyenLieu->so_luong_ton ?? 0;
                $donVi = $items->pluck('don_vi_nhap')->filter()->first() ?? '';

                $conHan = 0;
                $sapHetHan = 0;
                $hetHan = 0;

                $hanGanNhat = null;

                foreach ($items as $item) {
                    if (!$item->han_su_dung) continue;

                    $han = Carbon::parse($item->han_su_dung);

                    if ($han->gt($today->copy()->addDays(7))) {
                        $conHan += $item->so_luong_nhap;
                    } elseif ($han->between($today, $today->copy()->addDays(7))) {
                        $sapHetHan += $item->so_luong_nhap;
                    } elseif ($han->lt($today)) {
                        $hetHan += $item->so_luong_nhap;
                    }

                    // Cập nhật hạn gần nhất còn hiệu lực
                    if ($han->gte($today)) {
                        if (!$hanGanNhat || $han->lt($hanGanNhat)) {
                            $hanGanNhat = $han;
                        }
                    }
                }

                $ngayConLai = $hanGanNhat ? $today->diffInDays($hanGanNhat) : 'Đã hết hạn';

                $results[] = [
                    'nguyen_lieu' => $tenNguyenLieu,
                    'so_luong_ton' => $soLuongTon,
                    'so_ngay_con_lai' => $ngayConLai,
                    'con_han' => $conHan,
                    'sap_het_han' => $sapHetHan,
                    'het_han' => $hetHan,
                    'don_vi' => $donVi,
                    
                ];
            }

            return response()->json(['data' => $results]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi xử lý HanSuDung: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'error' => 'Lỗi server khi kiểm tra hạn sử dụng.'
            ], 500);
        }
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
