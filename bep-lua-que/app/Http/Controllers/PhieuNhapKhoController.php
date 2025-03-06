<?php

namespace App\Http\Controllers;


use App\Http\Requests\StorePhieuNhapKhoRequest;
use Illuminate\Http\Request;
use App\Models\PhieuNhapKho;
use App\Models\ChiTietPhieuNhapKho;
use App\Models\NhanVien;
use App\Models\NhaCungCap;
use App\Models\LoaiNguyenLieu;
use App\Models\NguyenLieu;
use Illuminate\Support\Facades\DB;

class PhieuNhapKhoController extends Controller
{
    /**

     * Danh sách phiếu nhập kho.
     */
    public function index(Request $request)
    {
        // Khởi tạo query lấy tất cả phiếu nhập kho (bao gồm cả bị xóa mềm) với các quan hệ
        $query = PhieuNhapKho::withTrashed()->with(['chiTietPhieuNhapKho', 'nhaCungCap', 'nhanVien']);

        // Lọc theo mã phiếu nhập
        if ($request->has('ma_phieu_nhap') && $request->ma_phieu_nhap != '') {
            $query->where('ma_phieu_nhap', 'like', '%' . $request->ma_phieu_nhap . '%');
        }

        // Lọc theo nhà cung cấp
        if ($request->has('nha_cung_cap') && $request->nha_cung_cap != '') {
            $query->whereHas('nhaCungCap', function ($q) use ($request) {
                $q->where('ten_nha_cung_cap', 'like', '%' . $request->nha_cung_cap . '%');
            });
        }

        // Lọc theo nhân viên
        if ($request->has('nhan_vien') && $request->nhan_vien != '') {
            $query->whereHas('nhanVien', function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->nhan_vien . '%');
            });
        }

        // Lọc theo trạng thái (có xóa mềm hay không)
        if ($request->has('trang_thai') && $request->trang_thai != '') {
            if ($request->trang_thai === 'da_xoa') {
                $query->onlyTrashed(); // Lấy chỉ các phiếu đã xóa mềm
            } elseif ($request->trang_thai === 'con_hoat_dong') {
                $query->whereNull('deleted_at'); // Lấy chỉ các phiếu chưa bị xóa mềm
            }
        }

        // Phân trang dữ liệu
        $data = $query->latest('id')->paginate(10);

        // Nếu là request AJAX, trả về HTML danh sách phiếu nhập

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.phieunhap.body-list', compact('data'))->render(),
            ]);
        }

        // Trả về view danh sách phiếu nhập
        return view('admin.phieunhap.list', [
            'data' => $data,
            'route' => route('phieu-nhap-kho.index'), // URL route cho AJAX
            'tableId' => 'list-container', // ID của bảng
            'searchInputId' => 'search-name', // ID của ô tìm kiếm
        ]);
    }



    /**
     * Hiển thị form tạo mới phiếu nhập kho.
     */
    public function create()
    {
        // Tự động tạo mã phiếu nhập (giả sử theo định dạng "PNK-YYYYMMDD-HHMMSS")
        $maPhieuNhap = 'PNK-' . now()->format('Ymd-His');

        // Lấy danh sách nhà cung cấp và loại nguyên liệu từ database
        $nhanViens = NhanVien::all();

        $nhaCungCaps = NhaCungCap::all();
        $loaiNguyenLieus = LoaiNguyenLieu::all();
        $nguyenLieus = NguyenLieu::all();


        return view('admin.phieunhap.create', compact('maPhieuNhap', 'nhanViens', 'nhaCungCaps', 'loaiNguyenLieus', 'nguyenLieus'));
    }




    public function store(StorePhieuNhapKhoRequest $request)
    {
        DB::beginTransaction();
        try {
            // Tạo phiếu nhập kho với trạng thái mặc định là "chờ duyệt"
            $phieuNhapKho = PhieuNhapKho::create([
                'ma_phieu_nhap' => 'PN-' . date('Ymd') . '-' . uniqid(),
                'nhan_vien_id' => $request->nhan_vien_id,
                'nha_cung_cap_id' => $request->nha_cung_cap_id,
                'ngay_nhap' => $request->ngay_nhap,
                'ghi_chu' => $request->ghi_chu,

            ]);

            foreach ($request->nguyen_lieu as $chiTiet) {
                $hinhAnhPath = null;
                if (isset($chiTiet['hinh_anh']) && $chiTiet['hinh_anh'] instanceof \Illuminate\Http\UploadedFile) {
                    $hinhAnhPath = $chiTiet['hinh_anh']->store('uploads/nguyen_lieu', 'public');
                }

                $heSoQuyDoi = $chiTiet['he_so_quy_doi'] ?? 1;
                $soLuongQuyDoi = $chiTiet['so_luong'] * $heSoQuyDoi;

                // Tìm hoặc tạo nguyên liệu
                $nguyenLieu = NguyenLieu::where('ten_nguyen_lieu', $chiTiet['ten_nguyen_lieu'])
                    ->where('loai_nguyen_lieu_id', $chiTiet['loai_nguyen_lieu_id'])
                    ->first();

                if (!$nguyenLieu) {
                    $nguyenLieu = NguyenLieu::create([
                        'ma_nguyen_lieu' => 'NL-' . date('Ymd') . '-' . uniqid(),
                        'ten_nguyen_lieu' => $chiTiet['ten_nguyen_lieu'],
                        'loai_nguyen_lieu_id' => $chiTiet['loai_nguyen_lieu_id'],
                        'don_vi_tinh' => $chiTiet['don_vi_tinh'],
                        'he_so_quy_doi' => $heSoQuyDoi,
                        'gia_nhap' => $chiTiet['don_gia'],
                        'mo_ta' => $chiTiet['mo_ta'],
                        'hinh_anh' => $hinhAnhPath,
                    ]);
                }

                // Lưu vào bảng chi tiết phiếu nhập kho
                ChiTietPhieuNhapKho::create([
                    'phieu_nhap_kho_id' => $phieuNhapKho->id,
                    'nguyen_lieu_id' => $nguyenLieu->id,
                    'so_luong' => $chiTiet['so_luong'],
                    'don_vi_nhap' => $chiTiet['don_vi_nhap'],
                    
                    'so_luong_quy_doi' => $soLuongQuyDoi,
                    'don_gia' => $chiTiet['don_gia'],
                    'tong_tien' => $chiTiet['so_luong'] * $chiTiet['don_gia'],
                    'han_su_dung' => $chiTiet['han_su_dung'] ?? null,
                    'trang_thai' => $chiTiet['trang_thai'] ?? 'Cần kiểm tra', // Lấy trạng thái từ form hoặc mặc định
                ]);
            }

            DB::commit();
            return redirect()->route('phieu-nhap-kho.index')->with('success', 'Tạo phiếu nhập kho thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Lỗi nhập kho: ' . $e->getMessage());
        }
    }





    // /**
    //  * Hiển thị chi tiết phiếu nhập kho.
    //  */
    public function show($id)
    {
        // Tải thông tin phiếu nhập kho cùng các liên kết liên quan
        $phieuNhapKho = PhieuNhapKho::with([
            'chiTietPhieuNhapKho.nguyenLieu.loaiNguyenLieu', // Lấy thông tin nguyên liệu và loại nguyên liệu
            'nhanVien', // Lấy thông tin nhân viên nhập
            'nhaCungCap' // Lấy thông tin nhà cung cấp
        ])->findOrFail($id);

        // Trả dữ liệu về view chi tiết phiếu nhập
        return view('admin.phieunhap.detail', compact('phieuNhapKho'));
    }

    public function xemChiTietNguyenLieu($phieuNhapId, $nguyenLieuId)
    {
        // Lấy chi tiết nguyên liệu từ bảng chi tiết phiếu nhập
        $chiTiet = ChiTietPhieuNhapKho::where('phieu_nhap_kho_id', $phieuNhapId)
            ->where('nguyen_lieu_id', $nguyenLieuId)
            ->with('nguyenLieu.loaiNguyenLieu')
            ->first();

        if (!$chiTiet) {
            return redirect()->route('phieu-nhap-kho.show', ['id' => $phieuNhapId])
                ->with('error', 'Nguyên liệu không tồn tại trong phiếu nhập.');
        }

        // Lấy số lượng tồn từ bảng nguyên liệu
        $soLuongTon = $chiTiet->nguyenLieu->so_luong_ton ?? 0;

        return view('admin.phieunhap.detail-nguyenlieu', compact('chiTiet', 'phieuNhapId', 'soLuongTon'));
    }
    public function capNhatTrangThai(Request $request, $phieuNhapId, $nguyenLieuId)
{
    // Lấy phiếu nhập kho
    $phieuNhap = PhieuNhapKho::findOrFail($phieuNhapId);

    // Kiểm tra nếu phiếu đã duyệt thì không được cập nhật trạng thái
    if ($phieuNhap->trang_thai === 'da_duyet') {
        return redirect()->back()->with(['error' => 'Không thể thay đổi trạng thái vì phiếu đã duyệt.']);
    }

    // Lấy chi tiết nguyên liệu trong phiếu nhập kho
    $chiTiet = ChiTietPhieuNhapKho::where('phieu_nhap_kho_id', $phieuNhapId)
        ->where('nguyen_lieu_id', $nguyenLieuId)
        ->firstOrFail();

    // Cập nhật trạng thái
    $chiTiet->trang_thai = $request->input('trang_thai');
    $chiTiet->save();

    // Lấy số lượng tồn để truyền lại view
    $soLuongTon = $chiTiet->nguyenLieu->so_luong_ton ?? 0;

    return redirect()->back()->with(['success' => 'Trạng thái đã cập nhật thành công.']);
}
    public function duyet($id)
    {
        DB::beginTransaction();
        try {
            $phieuNhapKho = PhieuNhapKho::findOrFail($id);

            // Kiểm tra trạng thái phiếu nhập phải là "chờ duyệt"
            if ($phieuNhapKho->trang_thai !== 'cho_duyet') {
                return redirect()->back()->withErrors('Phiếu này đã được duyệt hoặc hủy.');
            }

            // Cập nhật trạng thái phiếu nhập thành "đã duyệt"
            $phieuNhapKho->update(['trang_thai' => 'da_duyet']);

            // Lấy danh sách chi tiết phiếu nhập kho
            $chiTietPhieuNhap = $phieuNhapKho->chiTietPhieuNhapKho;

            // Kiểm tra nếu không có chi tiết nào
            if ($chiTietPhieuNhap->isEmpty()) {
                return redirect()->back()->withErrors('Phiếu nhập không có nguyên liệu.');
            }

            // Cập nhật số lượng nguyên liệu trong kho
            foreach ($chiTietPhieuNhap as $chiTiet) {
                $nguyenLieu = NguyenLieu::find($chiTiet->nguyen_lieu_id);
                if ($nguyenLieu) {
                    // Cộng số lượng quy đổi vào số lượng tồn
                    $nguyenLieu->so_luong_ton += $chiTiet->so_luong_quy_doi;
                    $nguyenLieu->gia_nhap = $chiTiet->don_gia;
                    $nguyenLieu->save();
                } else {
                    return redirect()->back()->withErrors('Nguyên liệu không tồn tại.');
                }
            }

            DB::commit();
            return back()->with('success', 'Phiếu nhập kho đã được duyệt.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Lỗi duyệt phiếu: ' . $e->getMessage());
        }
    }


    public function huy($id)
    {
        $phieu = PhieuNhapKho::findOrFail($id);
        $phieu->update(['trang_thai' => 'huy']);

        return back()->with('error', 'Phiếu nhập đã bị hủy!');
    }





    /**
     * Xóa phiếu nhập kho.
     */
    public function destroy($id)
    {
        // Tìm phiếu nhập kho
        $phieuNhapKho = PhieuNhapKho::findOrFail($id);

        // Giảm số lượng tồn của nguyên liệu dựa trên tổng số lượng trong phiếu nhập kho
        foreach ($phieuNhapKho->chiTietPhieuNhapKho as $chiTiet) {
            $nguyenLieu = $chiTiet->nguyenLieu;
            if ($nguyenLieu) {
                $nguyenLieu->so_luong_ton -= $chiTiet->so_luong; // Giảm tổng số lượng tồn
                $nguyenLieu->save();
            }
        }

        // Xóa phiếu nhập kho
        $phieuNhapKho->delete();

        return redirect()->route('phieu-nhap-kho.index')->with('success', 'Xóa phiếu nhập kho thành công.');
    }

    public function restore($id)
    {
        // Tìm phiếu nhập kho bao gồm cả những phiếu đã bị xóa mềm
        $phieuNhapKho = PhieuNhapKho::withTrashed()->findOrFail($id);

        // Kiểm tra xem nhà cung cấp của phiếu nhập có bị xóa mềm không
        if ($phieuNhapKho->nhaCungCap && $phieuNhapKho->nhaCungCap->deleted_at !== null) {
            return redirect()->back()->withErrors(['error' => 'Nhà cung cấp của phiếu nhập đã bị xóa mềm. Vui lòng khôi phục nhà cung cấp trước.']);
        }

        // Kiểm tra xem nhân viên nhập kho có bị xóa mềm không
        if ($phieuNhapKho->nhanVien && $phieuNhapKho->nhanVien->deleted_at !== null) {
            return redirect()->back()->withErrors(['error' => 'Nhân viên nhập kho đã bị xóa mềm. Vui lòng khôi phục nhân viên trước.']);
        }

        // Tăng số lượng tồn của nguyên liệu dựa trên tổng số lượng trong phiếu nhập kho
        foreach ($phieuNhapKho->chiTietPhieuNhapKho as $chiTiet) {
            $nguyenLieu = $chiTiet->nguyenLieu;
            if ($nguyenLieu) {
                $nguyenLieu->so_luong_ton += $chiTiet->so_luong; // Tăng tổng số lượng tồn
                $nguyenLieu->save();
            }
        }

        // Khôi phục phiếu nhập kho
        $phieuNhapKho->restore();

        return redirect()->route('phieu-nhap-kho.index')->with('success', 'Khôi phục phiếu nhập kho thành công!');
    }
}
