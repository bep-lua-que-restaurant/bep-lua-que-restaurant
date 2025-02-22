<?php

namespace App\Http\Controllers;

use App\Models\ChucVu;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreNhanVienRequest;
use App\Http\Requests\UpdateNhanVienRequest;

class NhanVienController extends Controller
{
    public function index()
    {
        $searchInputId = 'searchInput';
        $nhanViens = NhanVien::with('chucVu')->paginate(10);
        return view('admin.nhanvien.index', compact('nhanViens', 'searchInputId'));
    }

    public function create()
    {
        $chucVus = ChucVu::all();
        return view('admin.nhanvien.create', compact('chucVus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:nhan_viens,email',
            'so_dien_thoai' => 'required|unique:nhan_viens,so_dien_thoai',
            'chuc_vu_id' => 'required|exists:chuc_vus,id',
            'password' => 'required|min:6',
            'gioi_tinh' => 'required|in:nam,nu',
            'ngay_sinh' => 'nullable|date',
            'ngay_vao_lam' => 'nullable|date',
            'dia_chi' => 'nullable|string|max:255',
            'hinh_thuc_luong' => 'required|in:thang,ca,gio',
            'muc_luong' => 'required|numeric|min:0',
        ]);



        $maNhanVien = 'NV' . str_pad(NhanVien::count() + 1, 4, '0', STR_PAD_LEFT);

        // Tạo nhân viên
        $nhanVien = NhanVien::create([
            'ma_nhan_vien' => $maNhanVien,
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'chuc_vu_id' => $request->chuc_vu_id,
            'gioi_tinh' => $request->gioi_tinh,
            'ngay_sinh' => $request->ngay_sinh,
            'ngay_vao_lam' => $request->ngay_vao_lam,
            'dia_chi' => $request->dia_chi,
            'password' => Hash::make($request->password),
        ]);

        $nhanVien->luong()->create([
            'hinh_thuc' => $request->hinh_thuc_luong,
            'muc_luong' => $request->muc_luong,
        ]);

        return redirect()->route('nhan-vien.index')->with('success', 'Thêm nhân viên thành công!');
    }

    public function show($id)
    {
        $nhanVien = NhanVien::with('chucVu')->findOrFail($id);
        return view('admin.nhanvien.detail', compact('nhanVien'));
    }

    public function edit($id)
    {
        $nhanVien = NhanVien::findOrFail($id);
        $chucVus = ChucVu::all();
        return view('admin.nhanvien.edit', compact('nhanVien', 'chucVus'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:nhan_viens,email,' . $id,
            'so_dien_thoai' => 'required|unique:nhan_viens,so_dien_thoai,' . $id,
            'chuc_vu_id' => 'required|exists:chuc_vus,id',
            'password' => 'nullable|min:6',
            'gioi_tinh' => 'required|in:nam,nu',
            'ngay_sinh' => 'nullable|date',
            'ngay_vao_lam' => 'nullable|date',
            'dia_chi' => 'nullable|string|max:255',
            'hinh_thuc_luong' => 'required|in:thang,ca,gio',
            'muc_luong' => 'required|numeric|min:0',
        ]);

        $nhanVien = NhanVien::findOrFail($id);

        $data = $request->only([
            'ho_ten',
            'email',
            'so_dien_thoai',
            'chuc_vu_id',
            'gioi_tinh',
            'ngay_sinh',
            'ngay_vao_lam',
            'dia_chi'
        ]);

        // Nếu có nhập mật khẩu mới, mã hóa và cập nhật
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Cập nhật thông tin nhân viên
        $nhanVien->update($data);

        // Cập nhật lương của nhân viên
        $nhanVien->luong()->updateOrCreate(
            ['nhan_vien_id' => $nhanVien->id],
            [
                'hinh_thuc' => $request->hinh_thuc_luong,
                'muc_luong' => $request->muc_luong,
            ]
        );

        return redirect()->route('nhan-vien.index')->with('success', 'Cập nhật nhân viên thành công!');
    }



    public function destroy($id)
    {
        $nhanVien = NhanVien::findOrFail($id);

        // Xóa vĩnh viễn bản ghi
        $nhanVien->forceDelete();

        return redirect()->route('nhan-vien.index')->with('success', 'Xóa nhân viên thành công!');
    }

    public function nghiViec($id)
    {
        $nhanVien = NhanVien::findOrFail($id);
        $nhanVien->trang_thai = 'nghi_viec'; // Hoặc trạng thái nghỉ việc bạn sử dụng
        $nhanVien->save();

        return redirect()->route('nhan-vien.index')->with('success', 'Nhân viên đã nghỉ việc.');
    }

    public function khoiPhuc($id)
    {
        $nhanVien = NhanVien::findOrFail($id);
        $nhanVien->trang_thai = 'dang_lam_viec'; // Hoặc trạng thái đang làm việc bạn sử dụng
        $nhanVien->save();

        return redirect()->route('nhan-vien.index')->with('success', 'Nhân viên đã được khôi phục.');
    }
}
