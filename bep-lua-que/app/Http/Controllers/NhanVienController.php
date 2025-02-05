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
            'ho_ten' => 'required',
            'email' => 'required|email|unique:nhan_viens',
            'so_dien_thoai' => 'required|unique:nhan_viens',
            'chuc_vu_id' => 'required',
            'password' => 'required|min:6',
        ]);

        // Tạo mã nhân viên tự động
        $maNhanVien = 'NV' . str_pad(NhanVien::count() + 1, 4, '0', STR_PAD_LEFT);

        // Tạo nhân viên
        NhanVien::create([
            'ma_nhan_vien' => $maNhanVien,
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'chuc_vu_id' => $request->chuc_vu_id,
            'password' => Hash::make($request->password), // Mã hóa mật khẩu trước khi lưu
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
            'ho_ten' => 'required',
            'email' => 'required|email|unique:nhan_viens,email,' . $id,
            'so_dien_thoai' => 'required|unique:nhan_viens,so_dien_thoai,' . $id,
            'chuc_vu_id' => 'required',
            'password' => 'nullable|min:6', // Không bắt buộc nhập mật khẩu, nếu nhập phải ít nhất 6 ký tự
        ]);

        $nhanVien = NhanVien::findOrFail($id);

        $data = $request->only(['ho_ten', 'email', 'so_dien_thoai', 'chuc_vu_id']);

        // Nếu có nhập mật khẩu mới, mã hóa và cập nhật
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $nhanVien->update($data);

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
