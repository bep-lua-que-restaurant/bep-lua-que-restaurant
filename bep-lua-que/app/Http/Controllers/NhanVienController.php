<?php

namespace App\Http\Controllers;

use App\Exports\NhanVienExport;
use App\Imports\NhanVienImport;
use App\Models\ChucVu;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreNhanVienRequest;
use App\Http\Requests\UpdateNhanVienRequest;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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
        $request->merge([
            'ngay_ap_dung' => $request->ngay_ap_dung . '-01'
        ]);
       // Validate input data với thông báo lỗi tùy chỉnh
         $request->validate([
        'ho_ten' => 'required|string|max:255',
        'email' => 'required|email|unique:nhan_viens,email',
        'so_dien_thoai' => 'required|unique:nhan_viens,so_dien_thoai|regex:/^0\d{9}$/',
        'chuc_vu_id' => 'required|exists:chuc_vus,id',
        'password' => 'required|min:6',
        'gioi_tinh' => 'required|in:nam,nu',
        'ngay_sinh' => 'nullable|date',
        'ngay_vao_lam' => 'nullable|date',
        'dia_chi' => 'nullable|string|max:255',
        'hinh_thuc_luong' => 'required|in:ca,thang,gio',
        'muc_luong' => 'required|numeric|min:0',
        'ngay_ap_dung' => [
         'required',
         'date',
        'after_or_equal:' . now()->startOfMonth()->toDateString(),
        'before_or_equal:' . now()->endOfMonth()->toDateString(),
         'unique:luongs,ngay_ap_dung,NULL,id,nhan_vien_id,' . ($request->nhan_vien_id ?? 'NULL')
        ],
        'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        
    ], [
        'ho_ten.required' => 'Họ tên là bắt buộc.',
        'email.required' => 'Email là bắt buộc.',
        'email.email' => 'Email phải có định dạng hợp lệ.',
        'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
        'so_dien_thoai.regex' => 'Số điện thoại không đúng định dạng.',
        'chuc_vu_id.required' => 'Chức vụ là bắt buộc.',
        'password.required' => 'Mật khẩu là bắt buộc.',
        'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        'gioi_tinh.required' => 'Giới tính là bắt buộc.',
        'hinh_thuc_luong.required' => 'Hình thức lương là bắt buộc.',
        'muc_luong.required' => 'Mức lương là bắt buộc.',
        'muc_luong.numeric' => 'Mức lương phải là một số.',
        'hinh_anh.image' => 'Hình ảnh phải là file ảnh.',
        'hinh_anh.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif, hoặc webp.',
        'hinh_anh.max' => 'Hình ảnh không được vượt quá 2MB.',
        'ngay_ap_dung.required' => 'Tháng áp dụng lương là bắt buộc',
    ]);



        // Lấy mã nhân viên mới không bị trùng
        $lastNhanVien = NhanVien::orderBy('id', 'desc')->first();
        $nextId = $lastNhanVien ? ((int)substr($lastNhanVien->ma_nhan_vien, 2)) + 1 : 1;
        $maNhanVien = 'NV' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        if ($request->hasFile('hinh_anh') && $request->file('hinh_anh')->isValid()) {
            $hinhAnhPath = $request->file('hinh_anh')->store('hinh_anh', 'public');
        } else {
            // Handle the case when the file is not uploaded or invalid
            $hinhAnhPath = null; // or set a default image path
        }
        

        
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
            'password' => Hash::make($request->password),'hinh_anh' => $hinhAnhPath, // Lưu đường dẫn ảnh vào DB
        ]);

        $nhanVien->luong()->create([
            'hinh_thuc' => $request->hinh_thuc_luong,
            'muc_luong' => $request->muc_luong,
            'ngay_ap_dung'=>$request->ngay_ap_dung,
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
        'so_dien_thoai' => 'required|unique:nhan_viens,so_dien_thoai,' . $id . '|regex:/^0\d{9}$/',
        'chuc_vu_id' => 'required|exists:chuc_vus,id',
        'password' => 'nullable|min:6', // Để nullable vì không phải lúc nào cũng cần thay đổi mật khẩu
        'gioi_tinh' => 'required|in:nam,nu',
        'ngay_sinh' => 'nullable|date',
        'ngay_vao_lam' => 'nullable|date',
        'dia_chi' => 'nullable|string|max:255',
        'hinh_thuc_luong' => 'required|in:ca,thang,gio',
        'muc_luong' => 'required|numeric|min:0',
        //  'ngay_ap_dung' => 'nullable|date|after_or_equal:' . now()->addMonth()->startOfMonth()->toDateString(),


        'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ], [
        'ho_ten.required' => 'Họ tên là bắt buộc.',
        'email.required' => 'Email là bắt buộc.',
        'email.email' => 'Email phải có định dạng hợp lệ.',
        'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
        'so_dien_thoai.regex' => 'Số điện thoại không đúng định dạng.',
        'chuc_vu_id.required' => 'Chức vụ là bắt buộc.',
        'password.required' => 'Mật khẩu là bắt buộc.',
        'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        'gioi_tinh.required' => 'Giới tính là bắt buộc.',
        'hinh_thuc_luong.required' => 'Hình thức lương là bắt buộc.',
        'muc_luong.required' => 'Mức lương là bắt buộc.',
        'muc_luong.numeric' => 'Mức lương phải là một số.',
        'hinh_anh.image' => 'Hình ảnh phải là file ảnh.',
        'hinh_anh.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif, hoặc webp.',
        'hinh_anh.max' => 'Hình ảnh không được vượt quá 2MB.',
      
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

    // Xử lý ảnh nếu có
    if ($request->hasFile('hinh_anh')) {
        // Xóa ảnh cũ nếu có (nếu bạn muốn xóa ảnh cũ trước khi cập nhật)
        if ($nhanVien->hinh_anh) {
            Storage::delete('public/' . $nhanVien->hinh_anh);
        }

        // Lưu ảnh mới và lấy đường dẫn
        $hinhAnhPath = $request->file('hinh_anh')->store('hinh_anh', 'public');
        $data['hinh_anh'] = $hinhAnhPath;
    }

    // Cập nhật thông tin nhân viên
    $nhanVien->update($data);

    $ngayApDung = $request->ngay_ap_dung;
    if (strlen($ngayApDung) === 7) { // nếu là dạng YYYY-MM
    $ngayApDung .= '-01'; // gán thêm ngày đầu tháng
    }
    $existingLuong = $nhanVien->luong()->where([
        'hinh_thuc' => $request->hinh_thuc_luong,
        'muc_luong' => $request->muc_luong,
       'ngay_ap_dung' => $ngayApDung,
    ])->first();
    
    if (!$existingLuong) {
        $nhanVien->luong()->create([
            'nhan_vien_id' => $nhanVien->id,
            'hinh_thuc' => $request->hinh_thuc_luong,
            'muc_luong' => $request->muc_luong,
           'ngay_ap_dung' => $ngayApDung,
        ]);
    }
    


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
    public function exportNhanVien()
    {
        return Excel::download(new NhanVienExport, 'NhanVien.xlsx');
    }

    public function importNhanVien(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new NhanVienImport, $request->file('file'));

        return back()->with('success', 'Nhập dữ liệu thành công!');
    }
}
