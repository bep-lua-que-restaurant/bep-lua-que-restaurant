<?php

namespace App\Http\Controllers;

use App\Models\PhieuNhapKho;
use App\Models\ChiTietPhieuNhapKho;
use App\Models\NguyenLieu;
use App\Models\NhaCungCap;
use App\Models\NhanVien;
use App\Http\Requests\StorePhieuNhapKhoRequest;
use App\Http\Requests\UpdatePhieuNhapKhoRequest;
use App\Models\LoaiNguyenLieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PhieuNhapKhoController extends Controller
{
    /**
     * Hiển thị danh sách phiếu nhập kho.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = PhieuNhapKho::query()->with(['nhacungcap'])->withTrashed();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('trang_thai', function ($row) {
                    return $row->deleted_at
                        ? '<div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Đã xóa</div>'
                        : ($row->trang_thai == 'cho_duyet'
                            ? '<div class="d-flex align-items-center"><i class="fa fa-circle text-warning mr-1"></i> Chờ duyệt</div>'
                            : ($row->trang_thai == 'da_duyet'
                                ? '<div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Đã duyệt</div>'
                                : '<div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Đã hủy</div>'));
                })
                ->addColumn('tong_gia_tri', function ($row) {
                    return number_format($row->tong_tien, 0, ',', '.');  // Lấy trực tiếp giá trị từ cột tong_tien
                })
                ->addColumn('nhacungcap', function ($row) {
                    return $row->nhacungcap ? $row->nhacungcap->ten_nha_cung_cap : 'Chưa có';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex align-items-center">';

                    // Nút xem chi tiết
                    $html .= '<a href="' . route('phieu-nhap-kho.show', $row->id) . '" class="btn btn-info btn-sm p-2 m-2" title="Xem chi tiết"><i class="fa fa-eye"></i></a>';

                    // Nút chỉnh sửa
                    $html .= '<a href="' . route('phieu-nhap-kho.edit', $row->id) . '" class="btn btn-warning btn-sm p-2 m-2" title="Chỉnh sửa"><i class="fa fa-edit"></i></a>';

                    // Nút xóa hoặc khôi phục tùy theo trạng thái soft delete
                    if ($row->deleted_at) {
                        $html .= '<form action="' . route('phieu-nhap-kho.restore', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field()
                            . '<button type="submit" class="btn btn-success btn-sm p-2 m-2" title="Khôi phục"><i class="fa fa-recycle"></i></button>'
                            . '</form>';
                    } else {
                        $html .= '<form action="' . route('phieu-nhap-kho.destroy', $row->id) . '" method="POST" style="display:inline;">'
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

        return view('admin.phieunhapkho.list', [
            'route' => route('phieu-nhap-kho.index'),
            'tableId' => 'list-container',
            'searchInputId' => 'searchInput',
        ]);
    }


    /**
     * Hiển thị form thêm mới phiếu nhập kho.
     */
    public function create()
    {
        // Lấy danh sách nhà cung cấp, nhân viên và loại nguyên liệu
        $nhaCungCaps = NhaCungCap::all();
        $nhanViens = NhanVien::all();
        $loaiNguyenLieus = LoaiNguyenLieu::all();

        // Tạo mã phiếu tự động
        $latestPhieuNhapKho = PhieuNhapKho::latest()->first();
        $nextCode = $latestPhieuNhapKho ? 'PNK-' . str_pad(substr($latestPhieuNhapKho->ma_phieu, 4) + 1, 4, '0', STR_PAD_LEFT) : 'PNK-0001';

        return view('admin.phieunhapkho.create', compact('nhaCungCaps', 'nhanViens', 'loaiNguyenLieus', 'nextCode'));
    }

    public function store(StorePhieuNhapKhoRequest $request)
    {
        // Tạo phiếu nhập kho mới
        $phieuNhapKho = PhieuNhapKho::create([
            'ma_phieu' => $request->ma_phieu,
            'nha_cung_cap_id' => $request->nha_cung_cap_id,
            'nhan_vien_id' => $request->nhan_vien_id,
            'ngay_nhap' => now(),
            'ghi_chu' => $request->ghi_chu
        ]);

        // Biến để tính tổng tiền của phiếu nhập kho
        $tongTien = 0;

        // Lưu chi tiết phiếu nhập kho
        foreach ($request->ten_nguyen_lieus as $key => $tenNguyenLieu) {
            $thanhTien = $request->so_luong_nhaps[$key] * $request->don_gias[$key]; // Tính thành tiền cho chi tiết

            // Tính tổng tiền
            $tongTien += $thanhTien;

            // Lưu chi tiết phiếu nhập kho
            ChiTietPhieuNhapKho::create([
                'phieu_nhap_kho_id' => $phieuNhapKho->id,
                'ten_nguyen_lieu' => $tenNguyenLieu, // Tên nguyên liệu nhập tay từ form
                'loai_nguyen_lieu_id' => $request->loai_nguyen_lieu_ids[$key], // Lấy thông tin loại nguyên liệu từ form
                'don_vi_nhap' => $request->don_vi_nhaps[$key],
                'don_vi_ton' => $request->don_vi_tons[$key], // Lưu đơn vị tồn vào chi tiết phiếu nhập kho
                'so_luong_nhap' => $request->so_luong_nhaps[$key],
                'he_so_quy_doi' => $request->he_so_quy_dois[$key],
                'don_gia' => $request->don_gias[$key],
                'thanh_tien' => $thanhTien, // Lưu thành tiền vào chi tiết phiếu nhập kho
                'ngay_san_xuat' => $request->ngay_san_xuats[$key],
                'han_su_dung' => $request->ngay_het_hans[$key],
                'ghi_chu' => $request->ghi_chus[$key],
            ]);
        }

        // Cập nhật tổng tiền cho phiếu nhập kho
        $phieuNhapKho->update([
            'tong_tien' => $tongTien
        ]);

        return redirect()->route('phieu-nhap-kho.index')->with('success', 'Thêm phiếu nhập kho thành công!');
    }


    public function show(PhieuNhapKho $phieuNhapKho)
    {
        // Nếu đã bị xoá, chuyển về danh sách kèm thông báo lỗi
        if ($phieuNhapKho->deleted_at !== null) {
            return redirect()->route('phieu-nhap-kho.index')->with('error', 'Không thể xem phiếu nhập kho đã bị xoá.');
        }

        // Eager load các quan hệ cần thiết để tránh N+1 query
        $phieuNhapKho->load([
            'nhaCungCap',
            'nhanVien',
            'chiTietPhieuNhaps.nguyenLieu',
            'chiTietPhieuNhaps.loaiNguyenLieu'
        ]);

        $chiTietPhieuNhaps = $phieuNhapKho->chiTietPhieuNhaps;

        return view('admin.phieunhapkho.detail', compact('phieuNhapKho', 'chiTietPhieuNhaps'));
    }



    /**
     * Hiển thị form chỉnh sửa phiếu nhập kho.
     */
    public function edit(PhieuNhapKho $phieuNhapKho)
    {
        // Không cho sửa nếu đã bị xoá
        if ($phieuNhapKho->deleted_at !== null) {
            return redirect()->route('phieu-nhap-kho.index')->with('error', 'Không thể sửa phiếu nhập kho đã bị xoá.');
        }

        // Chỉ cho phép sửa nếu trạng thái là "chờ duyệt"
        if ($phieuNhapKho->trang_thai !== 'cho_duyet') {
            return redirect()->route('phieu-nhap-kho.index')->with('error', 'Chỉ được sửa phiếu ở trạng thái "Chờ duyệt".');
        }

        // Load dữ liệu cần thiết
        $phieuNhapKho->load('chiTietPhieuNhaps.nguyenLieu.loaiNguyenLieu');

        $nhaCungCaps = NhaCungCap::all();
        $nhanViens = NhanVien::all();
        $loaiNguyenLieus = LoaiNguyenLieu::all();

        return view('admin.phieunhapkho.edit', compact('phieuNhapKho', 'nhaCungCaps', 'nhanViens', 'loaiNguyenLieus'));
    }


    /**
     * Cập nhật phiếu nhập kho.
     */
    public function update(UpdatePhieuNhapKhoRequest $request, PhieuNhapKho $phieuNhapKho)
    {
        if ($phieuNhapKho->trang_thai !== 'cho_duyet') {
            return redirect()->back()->with('error', 'Không thể cập nhật phiếu đã duyệt hoặc đã hủy.');
        }

        // Cập nhật thông tin cơ bản
        $phieuNhapKho->update([
            'ma_phieu' => $request->ma_phieu,
            'nha_cung_cap_id' => $request->nha_cung_cap_id,
            'nhan_vien_id' => $request->nhan_vien_id,
            'ghi_chu' => $request->ghi_chu,
            'ngay_nhap' => $request->ngay_nhap ?? now()
        ]);

        // Cập nhật chi tiết phiếu nhập kho
        if ($request->has('chi_tiet_ids')) {
            foreach ($request->chi_tiet_ids as $index => $chiTietId) {
                $chiTiet = ChiTietPhieuNhapKho::find($chiTietId);

                if ($chiTiet) {
                    $chiTiet->update([
                        'ten_nguyen_lieu' => $request->ten_nguyen_lieus[$index],
                        'loai_nguyen_lieu_id' => $request->loai_nguyen_lieu_ids[$index],
                        'don_vi_nhap' => $request->don_vi_nhaps[$index],
                        'don_vi_ton' => $request->don_vi_tons[$index], // Lưu đơn vị tồn vào chi tiết phiếu nhập kho
                        'so_luong_nhap' => $request->so_luong_nhaps[$index],
                        'he_so_quy_doi' => $request->he_so_quy_dois[$index],
                        'don_gia' => $request->don_gias[$index],
                        'ngay_san_xuat' => $request->ngay_san_xuat[$index],
                        'han_su_dung' => $request->han_su_dung[$index],
                        'ghi_chu' => $request->ghi_chus[$index]
                    ]);
                }
            }
        }

        // Tính tổng tiền
        $tongTien = $phieuNhapKho->chiTietPhieuNhaps->sum(function ($ct) {
            return $ct->so_luong_nhap * $ct->don_gia;
        });

        // Cập nhật lại tổng tiền
        $phieuNhapKho->update(['tong_tien' => $tongTien]);

        return redirect()->route('phieu-nhap-kho.edit', $phieuNhapKho->id)
            ->with('success', 'Cập nhật phiếu nhập kho thành công!');
    }



    /**
     * Duyệt phiếu nhập kho và cập nhật nguyên liệu.
     */
    public function duyet($id)
    {
        $phieu = PhieuNhapKho::with('chiTietPhieuNhaps')->findOrFail($id);

        // Nếu phiếu đã huỷ thì không được duyệt nữa
        if ($phieu->trang_thai === 'da_huy') {
            return redirect()->back()->with('error', 'Phiếu đã bị hủy. Không thể duyệt.');
        }

        if ($phieu->trang_thai !== 'cho_duyet') {
            return redirect()->back()->with('error', 'Phiếu đã được xử lý.');
        }

        DB::beginTransaction();
        try {
            foreach ($phieu->chiTietPhieuNhaps as $chiTiet) {
                $nguyenLieu = NguyenLieu::where('ten_nguyen_lieu', $chiTiet->ten_nguyen_lieu)->first();

                if ($nguyenLieu) {
                    // Cập nhật số lượng tồn
                    $nguyenLieu->so_luong_ton += $chiTiet->so_luong_nhap * $chiTiet->he_so_quy_doi;
                    $nguyenLieu->save();
                } else {
                    // Tạo mới nguyên liệu
                    NguyenLieu::create([
                        'ten_nguyen_lieu' => $chiTiet->ten_nguyen_lieu,
                        'loai_nguyen_lieu_id' => $chiTiet->loai_nguyen_lieu_id,
                        'don_vi_ton' => $chiTiet->don_vi_ton,
                        'so_luong_ton' => $chiTiet->so_luong_nhap * $chiTiet->he_so_quy_doi,
                    ]);
                }
            }

            $phieu->trang_thai = 'da_duyet';
            $phieu->save();

            DB::commit();
            return redirect()->back()->with('success', 'Phiếu đã được duyệt thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hủy phiếu nhập kho.
     */
    public function huy($id)
    {
        $phieu = PhieuNhapKho::with('chiTietPhieuNhaps')->findOrFail($id);

        // Nếu đã huỷ rồi thì không huỷ lại nữa
        if ($phieu->trang_thai === 'da_huy') {
            return redirect()->back()->with('error', 'Phiếu đã bị hủy.');
        }

        DB::beginTransaction();
        try {
            // Nếu phiếu đã duyệt trước đó, cần rollback lại kho
            if ($phieu->trang_thai === 'da_duyet') {
                foreach ($phieu->chiTietPhieuNhaps as $chiTiet) {
                    $nguyenLieu = NguyenLieu::where('ten_nguyen_lieu', $chiTiet->ten_nguyen_lieu)->first();

                    if ($nguyenLieu) {
                        $soLuongTru = $chiTiet->so_luong_nhap * $chiTiet->he_so_quy_doi;

                        // Đảm bảo không trừ âm kho
                        $nguyenLieu->so_luong_ton = max(0, $nguyenLieu->so_luong_ton - $soLuongTru);
                        $nguyenLieu->save();
                    }
                }
            }

            // Cập nhật trạng thái phiếu là "đã huỷ"
            $phieu->trang_thai = 'da_huy';
            $phieu->save();

            DB::commit();
            return redirect()->back()->with('success', 'Phiếu đã được hủy thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi huỷ phiếu: ' . $e->getMessage());
        }
    }



    /**
     * Xóa phiếu nhập kho.
     */
    public function destroy(PhieuNhapKho $phieuNhapKho)
    {
        if (in_array($phieuNhapKho->trang_thai, ['da_duyet', 'da_huy'])) {
            $message = 'Không thể xoá phiếu đã duyệt hoặc đã huỷ.';

            // Nếu là AJAX thì trả về JSON thay vì redirect
            if (request()->ajax()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()->route('phieu-nhap-kho.index')->with('error', $message);
        }

        $phieuNhapKho->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Đã xoá phiếu nhập kho thành công!']);
        }

        return redirect()->route('phieu-nhap-kho.index')->with('success', 'Đã xoá phiếu nhập kho thành công!');
    }



    /**
     * Khôi phục phiếu nhập kho đã xóa.
     */
    public function restore($id)
    {
        $phieuNhapKho = PhieuNhapKho::withTrashed()->findOrFail($id);
        $phieuNhapKho->restore();

        return redirect()->route('phieu-nhap-kho.index')->with('success', 'Đã khôi phục phiếu nhập kho!');
    }
}
