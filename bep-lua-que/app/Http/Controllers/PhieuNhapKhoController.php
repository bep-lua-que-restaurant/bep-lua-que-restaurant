<?php

namespace App\Http\Controllers;

use App\Models\ChiTietNhapKho;
use App\Models\LoaiNguyenLieu;
use App\Models\NguyenLieu;
use App\Models\NhaCungCap;
use App\Models\NhanVien;
use App\Models\PhieuNhapKho;
use App\Http\Requests\StorePhieuNhapKhoRequest;
use App\Http\Requests\UpdatePhieuNhapKhoRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PhieuNhapKhoExport;
use App\Imports\PhieuNhapKhoImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PhieuNhapKhoController extends Controller
{
    /**
     * Hiển thị danh sách phiếu nhập kho với AJAX.
     */
    public function index(Request $request)
    {
        $query = PhieuNhapKho::query();

        if ($request->filled('ma_phieu_nhap')) {
            $query->where('ma_phieu_nhap', 'like', '%' . $request->ma_phieu_nhap . '%');
        }

        $data = $query->with(['nhanVien', 'nhaCungCap'])->latest('id')->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.phieunhap.body-list', compact('data'))->render(),
            ]);
        }

        return view('admin.phieunhap.list', [
            'data' => $data,
            'searchInputId' => 'search-phieu-nhap' //  Truyền biến này vào view
        ]);
    }


    /**
     * Hiển thị form tạo phiếu nhập.
     */
    public function create()
    {
        $nhaCungCaps = NhaCungCap::all();
        $nhanViens = NhanVien::all();
        $loaiNguyenLieus = LoaiNguyenLieu::all();

        return view('admin.phieunhap.create', compact('nhaCungCaps', 'nhanViens', 'loaiNguyenLieus'));
    }

    /**
     * Lưu phiếu nhập kho và cập nhật nguyên liệu.
     */
    public function store(StorePhieuNhapKhoRequest $request)
    {
        // Kiểm tra dữ liệu nhận từ form
        Log::info('Dữ liệu nhận được từ form:', $request->all());

        $data = $request->validated();

        // Tạo hoặc lấy nhà cung cấp (có thể không có)
        $nhaCungCap = null;
        if (!empty($data['nha_cung_cap_id'])) {
            $nhaCungCap = NhaCungCap::find($data['nha_cung_cap_id']);
        }

        // Tạo hoặc lấy nhân viên
        $nhanVien = NhanVien::findOrFail($data['nhan_vien_id']);

        // Tạo Phiếu Nhập Kho
        $phieuNhap = PhieuNhapKho::create([
            'ma_phieu_nhap' => 'PNK-' . time(),
            'nhan_vien_id' => $nhanVien->id,
            'nha_cung_cap_id' => $nhaCungCap ? $nhaCungCap->id : null,
            'ngay_nhap' => $data['ngay_nhap'],
            'tong_tien' => 0, // Cập nhật sau
            'trang_thai' => 'da_nhap',
        ]);

        Log::info('Phiếu nhập kho đã được tạo: ' . $phieuNhap->ma_phieu_nhap);

        $tongTien = 0;

        foreach ($data['ten_nguyen_lieu'] as $key => $tenNguyenLieu) {
            $loaiHang = LoaiNguyenLieu::findOrFail($data['loai_hang_id'][$key]);

            $donViTinh = $data['don_vi_tinh'][$key];
            $soLuong = $data['so_luong'][$key];
            $giaNhap = $data['gia_nhap'][$key];
            $thanhTien = $soLuong * $giaNhap;
            $tongTien += $thanhTien;

            //  Kiểm tra nếu nguyên liệu đã tồn tại
            $nguyenLieu = NguyenLieu::where('ten_nguyen_lieu', $tenNguyenLieu)
                ->where('loai_nguyen_lieu_id', $loaiHang->id)
                ->first();

            if (!$nguyenLieu) {
                $nguyenLieu = NguyenLieu::create([
                    'ma_nguyen_lieu' => 'ML-' . time(),
                    'ten_nguyen_lieu' => $tenNguyenLieu,
                    'loai_nguyen_lieu_id' => $loaiHang->id,
                    'don_vi_tinh' => $donViTinh,
                    'so_luong_ton' => 0,
                    'gia_nhap' => $giaNhap
                ]);
                Log::info("Nguyên liệu mới được tạo: " . $tenNguyenLieu);
            }

            //  Tạo chi tiết nhập kho
            ChiTietNhapKho::create([
                'phieu_nhap_kho_id' => $phieuNhap->id,
                'nguyen_lieu_id' => $nguyenLieu->id,
                'so_luong' => $soLuong,
                'gia_nhap' => $giaNhap,
                'thanh_tien' => $thanhTien,
            ]);

            // Cập nhật số lượng tồn kho
            $nguyenLieu->increment('so_luong_ton', $soLuong);
        }

        // Cập nhật tổng tiền của phiếu nhập
        $phieuNhap->update(['tong_tien' => $tongTien]);

        return redirect()->route('phieu-nhap-kho.index')
            ->with('success', 'Thêm phiếu nhập thành công!');
    }








    /**
     * Hiển thị chi tiết phiếu nhập kho.
     */
    public function show(PhieuNhapKho $phieuNhapKho)
    {
        $phieuNhapKho->load(['chiTietNhapKho.nguyenLieu', 'nhanVien', 'nhaCungCap']);

        return view('admin.phieunhap.detail', compact('phieuNhapKho'));
    }

    /**
     * Hiển thị form chỉnh sửa phiếu nhập.
     */
    public function edit(PhieuNhapKho $phieuNhapKho)
    {
        $nhaCungCaps = NhaCungCap::all();
        $nhanViens = NhanVien::all();

        return view('admin.phieunhap.edit', compact('phieuNhapKho', 'nhaCungCaps', 'nhanViens'));
    }

    /**
     * Cập nhật phiếu nhập kho.
     */
    public function update(UpdatePhieuNhapKhoRequest $request, PhieuNhapKho $phieuNhapKho)
    {
        $data = $request->validated();
        $phieuNhapKho->update($data);

        return back()->with('success', 'Cập nhật phiếu nhập thành công!');
    }

    /**
     * Xóa phiếu nhập kho (Soft Delete).
     */
    public function destroy(PhieuNhapKho $phieuNhapKho)
    {
        DB::transaction(function () use ($phieuNhapKho) {
            // Xóa chi tiết phiếu nhập trước khi xóa phiếu nhập
            ChiTietNhapKho::where('phieu_nhap_kho_id', $phieuNhapKho->id)->delete();

            // Xóa phiếu nhập kho
            $phieuNhapKho->delete();
        });

        return redirect()->route('phieu-nhap-kho.index')->with('success', 'Xóa phiếu nhập thành công!');
    }

    /**
     * Khôi phục phiếu nhập kho đã xóa.
     */
    public function restore($id)
    {
        $phieuNhapKho = PhieuNhapKho::withTrashed()->findOrFail($id);
        $phieuNhapKho->restore();

        return redirect()->route('phieu-nhap-kho.index')->with('success', 'Khôi phục phiếu nhập thành công!');
    }

    /**
     * Xuất danh sách phiếu nhập kho ra file Excel.
     */
    // public function export()
    // {
    //     return Excel::download(new PhieuNhapKhoExport, 'PhieuNhapKho.xlsx');
    // }

    // /**
    //  * Nhập dữ liệu phiếu nhập kho từ file Excel.
    //  */
    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls'
    //     ]);

    //     Excel::import(new PhieuNhapKhoImport, $request->file('file'));

    //     return back()->with('success', 'Nhập dữ liệu thành công!');
    // }
}
