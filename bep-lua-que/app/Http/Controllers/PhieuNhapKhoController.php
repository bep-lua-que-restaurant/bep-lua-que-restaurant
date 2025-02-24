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
        $data = $query->latest('id')->paginate(15);

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

    /**
     * Lưu phiếu nhập kho mới vào cơ sở dữ liệu.
     */
    /**
     * Lưu phiếu nhập kho mới vào cơ sở dữ liệu.
     */
    public function store(StorePhieuNhapKhoRequest $request)
    {
        // Tạo phiếu nhập kho
        $phieuNhapKho = PhieuNhapKho::create([
            'ma_phieu_nhap' => 'PN-' . date('Ymd') . '-' . uniqid(),
            'nhan_vien_id' => $request->nhan_vien_id,
            'nha_cung_cap_id' => $request->nha_cung_cap_id,
            'ngay_nhap' => $request->ngay_nhap,
            'ghi_chu' => $request->ghi_chu,
        ]);

        // Lặp qua từng nguyên liệu
        if (isset($request->nguyen_lieu) && is_array($request->nguyen_lieu)) {
            foreach ($request->nguyen_lieu as $chiTiet) {
                // Kiểm tra và xử lý hình ảnh
                $hinhAnhPath = null;
                if (isset($chiTiet['hinh_anh']) && $chiTiet['hinh_anh'] instanceof \Illuminate\Http\UploadedFile) {
                    $hinhAnhPath = $chiTiet['hinh_anh']->store('uploads/nguyen_lieu', 'public');
                }

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
                        'so_luong_ton' => $chiTiet['so_luong'],
                        'gia_nhap' => $chiTiet['don_gia'],
                        'hinh_anh' => $hinhAnhPath, // Lưu đường dẫn ảnh
                    ]);
                } else {
                    $nguyenLieu->increment('so_luong_ton', $chiTiet['so_luong']);
                }

                ChiTietPhieuNhapKho::create([
                    'phieu_nhap_kho_id' => $phieuNhapKho->id,
                    'nguyen_lieu_id' => $nguyenLieu->id,
                    'so_luong' => $chiTiet['so_luong'],
                    'don_gia' => $chiTiet['don_gia'],
                    'tong_tien' => $chiTiet['so_luong'] * $chiTiet['don_gia'],
                    'han_su_dung' => $chiTiet['han_su_dung'] ?? null,
                    'trang_thai' => 'Đạt',
                ]);
            }
        } else {
            return redirect()->back()->withErrors('Dữ liệu nguyên liệu không hợp lệ.');
        }

        return redirect()->route('phieu-nhap-kho.index')->with('success', 'Tạo phiếu nhập kho thành công.');
    }







    /**
     * Hiển thị chi tiết phiếu nhập kho.
     */
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

    public function duyet($id)
    {
        $phieu = PhieuNhapKho::findOrFail($id);
        $phieu->update(['trang_thai' => 'da_duyet']);

        return redirect()->route('phieu-nhap-kho.index')->with('success', 'Phiếu nhập đã được duyệt!');
    }

    public function huy($id)
    {
        $phieu = PhieuNhapKho::findOrFail($id);
        $phieu->update(['trang_thai' => 'huy']);

        return redirect()->route('phieu-nhap-kho.index')->with('error', 'Phiếu nhập đã bị hủy!');
    }
    // /**
    //  * Hiển thị form chỉnh sửa phiếu nhập kho.
    //  */
    // public function edit($id)
    // {
    //     // Lấy thông tin phiếu nhập kho cùng chi tiết và các quan hệ
    //     $phieuNhapKho = PhieuNhapKho::with('chiTietPhieuNhapKho')->findOrFail($id);

    //     // Lấy danh sách nhân viên, nhà cung cấp, loại nguyên liệu, và nguyên liệu từ database
    //     $nhanViens = NhanVien::all();
    //     $nhaCungCaps = NhaCungCap::all();
    //     $loaiNguyenLieus = LoaiNguyenLieu::all();
    //     $nguyenLieus = NguyenLieu::all();

    //     // Trả về view chỉnh sửa phiếu nhập với các dữ liệu liên quan
    //     return view('admin.phieunhap.edit', compact(
    //         'phieuNhapKho',
    //         'nhanViens',
    //         'nhaCungCaps',
    //         'loaiNguyenLieus',
    //         'nguyenLieus'
    //     ));
    // }


    /**
     * Cập nhật phiếu nhập kho.
     */

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
