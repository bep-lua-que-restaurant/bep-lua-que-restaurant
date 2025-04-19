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

    public function tonKhoXuatNhap(Request $request)
    {
        $ngay = $request->input('ngay') ? Carbon::parse($request->input('ngay')) : Carbon::today();
        $loaiId = $request->input('loai_nguyen_lieu_id');
    
        // Lấy ID nguyên liệu theo loại (có thể đã xóa mềm)
        $nguyenLieuIds = NguyenLieu::withTrashed()
            ->when($loaiId, fn($q) => $q->where('loai_nguyen_lieu_id', $loaiId))
            ->pluck('id');
    
        $nguyenLieus = NguyenLieu::withTrashed()
            ->whereIn('id', $nguyenLieuIds)
            ->get();
    
        $duLieu = $nguyenLieus->map(function ($nl) use ($ngay) {
            $id = $nl->id;
    
            // Tổng nhập trong ngày (phân loại)
            $nhapTuBep = DB::table('chi_tiet_phieu_nhap_khos')
                ->join('phieu_nhap_khos', 'chi_tiet_phieu_nhap_khos.phieu_nhap_kho_id', '=', 'phieu_nhap_khos.id')
                ->where('phieu_nhap_khos.trang_thai', 'da_duyet')
                ->whereDate('phieu_nhap_khos.ngay_nhap', $ngay) // Lọc theo ngày
                ->where('phieu_nhap_khos.loai_phieu', 'nhap_tu_bep')
                ->where('chi_tiet_phieu_nhap_khos.nguyen_lieu_id', $id)
                ->sum('so_luong_nhap');
    
            $nhapTuNCC = DB::table('chi_tiet_phieu_nhap_khos')
                ->join('phieu_nhap_khos', 'chi_tiet_phieu_nhap_khos.phieu_nhap_kho_id', '=', 'phieu_nhap_khos.id')
                ->where('phieu_nhap_khos.trang_thai', 'da_duyet')
                ->whereDate('phieu_nhap_khos.ngay_nhap', $ngay) // Lọc theo ngày
                ->where('phieu_nhap_khos.loai_phieu', 'nhap_tu_ncc')
                ->where('chi_tiet_phieu_nhap_khos.nguyen_lieu_id', $id)
                ->sum('so_luong_nhap');
    
            // Tổng xuất trong ngày (phân loại)
            $xuatBep = DB::table('chi_tiet_phieu_xuat_khos')
                ->join('phieu_xuat_khos', 'chi_tiet_phieu_xuat_khos.phieu_xuat_kho_id', '=', 'phieu_xuat_khos.id')
                ->where('phieu_xuat_khos.trang_thai', 'da_duyet')
                ->whereDate('phieu_xuat_khos.ngay_xuat', $ngay) // Lọc theo ngày
                ->where('phieu_xuat_khos.loai_phieu', 'xuat_bep')
                ->where('chi_tiet_phieu_xuat_khos.nguyen_lieu_id', $id)
                ->sum('so_luong');
    
            $xuatTraHang = DB::table('chi_tiet_phieu_xuat_khos')
                ->join('phieu_xuat_khos', 'chi_tiet_phieu_xuat_khos.phieu_xuat_kho_id', '=', 'phieu_xuat_khos.id')
                ->where('phieu_xuat_khos.trang_thai', 'da_duyet')
                ->whereDate('phieu_xuat_khos.ngay_xuat', $ngay) // Lọc theo ngày
                ->where('phieu_xuat_khos.loai_phieu', 'xuat_tra_hang')
                ->where('chi_tiet_phieu_xuat_khos.nguyen_lieu_id', $id)
                ->sum('so_luong');
    
            $xuatHuy = DB::table('chi_tiet_phieu_xuat_khos')
                ->join('phieu_xuat_khos', 'chi_tiet_phieu_xuat_khos.phieu_xuat_kho_id', '=', 'phieu_xuat_khos.id')
                ->where('phieu_xuat_khos.trang_thai', 'da_duyet')
                ->whereDate('phieu_xuat_khos.ngay_xuat', $ngay) // Lọc theo ngày
                ->where('phieu_xuat_khos.loai_phieu', 'xuat_huy')
                ->where('chi_tiet_phieu_xuat_khos.nguyen_lieu_id', $id)
                ->sum('so_luong');
    
            return [
                'id' => $id,
                'nguyen_lieu' => $nl->ten_nguyen_lieu,
                'don_vi' => $nl->don_vi_ton,
                'ton_kho_hien_tai' => $nl->so_luong_ton,
                'nhap_tu_bep' => $nhapTuBep,
                'nhap_tu_ncc' => $nhapTuNCC,
                'tong_nhap' => $nhapTuBep + $nhapTuNCC,
                'xuat_bep' => $xuatBep,
                'xuat_tra_hang' => $xuatTraHang,
                'xuat_huy' => $xuatHuy,
                'tong_xuat' => $xuatBep + $xuatTraHang + $xuatHuy,
                'da_ngung_su_dung' => $nl->trashed(),
            ];
        });
    
        return response()->json($duLieu);
    }
    





    public function viewTonKhoXuatDung()
    {
        $loaiNguyenLieus = LoaiNguyenLieu::all();
        return view('admin.nguyenlieu.kiemtratonkho', compact('loaiNguyenLieus'));
    }





    public function HanSuDung(Request $request)
    {
        try {
            $today = Carbon::now();

            // Lấy giá trị ngày và loại nguyên liệu từ request
            $ngay = $request->input('ngay');
            $loaiNguyenLieuId = $request->input('loai_nguyen_lieu_id');  // Lấy loại nguyên liệu từ form
            $ngay = $ngay ? Carbon::parse($ngay) : $today;  // Nếu không có ngày, sử dụng ngày hiện tại

            // Không eager load quan hệ lỗi
            $dsNhap = ChiTietPhieuNhapKho::with('nguyenLieu')
                ->where('so_luong_nhap', '>', 0)
                ->whereNotNull('han_su_dung')
                ->get();

            // Lọc theo ngày
            $dsNhap = $dsNhap->filter(function ($item) use ($ngay) {
                $hanSuDung = Carbon::parse($item->han_su_dung);
                return $hanSuDung >= $ngay;
            });

            // Lọc theo loại nguyên liệu nếu có
            if ($loaiNguyenLieuId) {
                $dsNhap = $dsNhap->filter(function ($item) use ($loaiNguyenLieuId) {
                    return $item->nguyenLieu->loai_nguyen_lieu_id == $loaiNguyenLieuId;
                });
            }

            // Nhóm theo nguyên liệu
            $grouped = $dsNhap->groupBy('nguyen_lieu_id');

            $results = [];

            foreach ($grouped as $nguyenLieuId => $danhSachNhap) {
                $firstItem = $danhSachNhap->first();

                if (!$firstItem || !$firstItem->nguyenLieu) {
                    continue;
                }

                $nguyenLieu = $firstItem->nguyenLieu;
                $tenNguyenLieu = $nguyenLieu->ten_nguyen_lieu;
                $donVi = $danhSachNhap->pluck('don_vi_nhap')->filter()->first() ?? '';

                $conHan = 0;
                $sapHetHan = 0;
                $hetHan = 0;
                $tongTon = 0;
                $hanGanNhat = null;

                $loNhap = [];

                foreach ($danhSachNhap as $item) {
                    $han = Carbon::parse($item->han_su_dung);
                    $ngayNhap = Carbon::parse($item->created_at); // hoặc $item->ngay_nhap
                    $soLuongTon = $item->so_luong_nhap;
                
                    if ($soLuongTon <= 0) continue;
                
                    $tongTon += $soLuongTon;
                
                    // Tình trạng
                    if ($han->gt($today->copy()->addDays(7))) {
                        $conHan += $soLuongTon;
                    } elseif ($han->between($today, $today->copy()->addDays(7))) {
                        $sapHetHan += $soLuongTon;
                    } elseif ($han->lt($today)) {
                        $hetHan += $soLuongTon;
                    }
                
                    // Dùng cho biểu đồ
                    $loNhap[] = [
                        'so_luong' => $soLuongTon,
                        'ngay_nhap' => $ngayNhap->format('d/m/Y'),
                        'han_su_dung' => $han->format('d/m/Y'),
                    ];
                }
                
               
                
                

                // $ngayConLai = $hanGanNhat ? $today->diffInDays($hanGanNhat) : 'Đã hết hạn';

                $results[] = [
                    'nguyen_lieu' => $tenNguyenLieu,
                    'so_luong_ton' => $tongTon,
                    'so_ngay_con_lai' => $hanGanNhat ? $today->diffInDays($hanGanNhat) : 'Đã hết hạn',
                    'con_han' => $conHan,
                    'sap_het_han' => $sapHetHan,
                    'het_han' => $hetHan,
                    'don_vi' => $donVi,
                    'lo_nhap' => $loNhap, // 🔥 thêm dữ liệu theo từng lô
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
