<?php

use App\Http\Controllers\BangTinhLuongController;
use App\Http\Controllers\CaLamNhanVienController;

use App\Http\Controllers\ChatController;

use App\Http\Controllers\ChucVuController;
use App\Http\Controllers\DanhMucMonAnController;
use App\Http\Controllers\ComBoController;
use App\Http\Controllers\DichVuController;
use App\Http\Controllers\BanAnController;
use App\Http\Controllers\DatBanController;
use App\Http\Controllers\LoaiNguyenLieuController;
use App\Http\Controllers\NguyenLieuController;
use App\Http\Controllers\PhieuXuatKhoController;
use App\Http\Controllers\PhongAnController;
use App\Http\Controllers\LuongController;
use App\Http\Controllers\ThongKeDoanhSoController;
use App\Http\Controllers\ThongKeMonAnController;
use App\Http\Controllers\ThongKeSoLuongHoaDonController;
use App\Http\Controllers\ThongKeSoLuongKhachController;
use App\Http\Controllers\ThongKeTopDoanhThuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TableBookedController;
use App\Http\Controllers\CaLamController;
use App\Http\Controllers\MonAnController;
use App\Http\Controllers\BepController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillImageController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\QuanLyController;
use App\Http\Controllers\ThuNganController;
use App\Http\Controllers\ChamCongController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\ThongKeController;
use App\Http\Controllers\PhieuNhapKhoController;
use App\Http\Controllers\MaGiamGiaController;
use App\Http\Controllers\TachBanController;
use App\Http\Controllers\XinNghiController;
use App\Http\Controllers\ThongKeSoBanController;

use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;
use App\Models\BanAn;
use App\Models\DatBan;
use Carbon\Carbon;


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// // Tất cả roles đều truy cập 
Route::middleware(['auth'])->group(function () {
    // Nếu là bếp, chuyển hướng ngay đến trang bếp
    Route::get('/', function () {
        if (auth()->check() && auth()->user()->role == 2) {
            return redirect()->route('bep.dashboard');
        }
        return redirect()->route('dashboard');
    });
    Route::get('/', [ThongKeController::class, 'index'])->name('dashboard');

    // Lễ tân (1) - Được vào lễ tân, thống kê, hóa đơn
    Route::middleware(['role:1'])->group(function () {
        Route::get('/letan', [DatBanController::class, 'index'])->name('letan.index');
        Route::get('/thongke', [ThongkeController::class, 'index'])->name('thongke.index');
        Route::get('/hoadon', [HoadonController::class, 'index'])->name('hoadon.index');
    });

    // Bếp (2) - Chỉ vào được bếp
    Route::middleware(['role:2'])->group(function () {
        Route::get('/bep', [BepController::class, 'index'])->name('bep.dashboard');
    });

    // Thu ngân (3) - Được vào thu ngân, lễ tân, thống kê, hóa đơn
    Route::middleware(['role:3'])->group(function () {
        Route::get('/thungan', [ThunganController::class, 'index'])->name('thungan.index');
        Route::get('/letan', [DatBanController::class, 'index'])->name('letan.index');
        Route::get('/thongke', [ThongkeController::class, 'index'])->name('thongke.index');
        Route::get('/hoadon', [HoadonController::class, 'index'])->name('hoadon.index');
    });

    // Quản lý (4) - Truy cập toàn bộ
    Route::middleware(['role:4'])->group(function () {
        Route::get('/quanly', [ThongKeController::class, 'index'])->name('quanly.index');
        Route::get('/letan', [DatBanController::class, 'index'])->name('letan.index');
        Route::get('/thongke', [ThongkeController::class, 'index'])->name('thongke.index');
        Route::get('/hoadon', [HoadonController::class, 'index'])->name('hoadon.index');
        Route::get('/bep', [BepController::class, 'index'])->name('bep.dashboard');
        Route::get('/thungan', [ThunganController::class, 'index'])->name('thungan.index');

        // Các tính năng khác của quản lý
        Route::get('/danh-muc-mon-an', [DanhMucMonAnController::class, 'index'])->name('danh-muc-mon-an.index');
        Route::get('/mon-an', [MonAnController::class, 'index'])->name('mon-an.index');
        Route::get('/dich-vu', [DichVuController::class, 'index'])->name('dich-vu.index');
        Route::get('/com-bo', [ComboController::class, 'index'])->name('com-bo.index');
        Route::get('/ma-giam-gia', [MaGiamGiaController::class, 'index'])->name('ma-giam-gia.index');
        Route::get('/ban-an', [BanAnController::class, 'index'])->name('ban-an.index');

        Route::get('/nhan-vien', [NhanVienController::class, 'index'])->name('nhan-vien.index');
        Route::get('/ca-lam', [CaLamController::class, 'index'])->name('ca-lam.index');
        Route::get('/ca-lam-nhan-vien', [CaLamNhanVienController::class, 'index'])->name('ca-lam-nhan-vien.index');
        Route::get('/cham-cong', [ChamCongController::class, 'index'])->name('cham-cong.index');
        Route::get('/luong', [LuongController::class, 'index'])->name('luong.index');
    });


    Route::get('/ban-an/ajax/{id}', [BanAnController::class, 'show'])->name('ban-an.ajax-show');


    Route::get('/', function () {
        return view('admin.dashboard');
    });

    Route::get('/', [ThongKeController::class, 'index'])->name('dashboard');
    Route::get('/thong-ke-doanh-so', [ThongKeDoanhSoController::class, 'index'])->name('thongke.thongkedoanhso');
    Route::get('/thong-ke-hoa-don', [ThongKeSoLuongHoaDonController::class, 'index'])->name('thongke.thongkehoadon');
    Route::get('/thong-ke-top-doanh-thu', [ThongKeTopDoanhThuController::class, 'index'])->name('thongke.topdoanhthu');

    Route::get('/thong-ke-so-luong-khach', [ThongKeSoLuongKhachController::class, 'index'])->name('thongke.thongkesoluongkhach');


    // Danh mục món ăn
    Route::resource('danh-muc-mon-an', DanhMucMonAnController::class);
    Route::post('danh-muc-mon-an/restore/{id}', [DanhMucMonAnController::class, 'restore'])->name('danh-muc-mon-an.restore');
    Route::post('/ban-an/restore/{id}', [BanAnController::class, 'restore'])->name('ban-an.restore');

    Route::get('export-danh-muc-mon-an', [DanhMucMonAnController::class, 'export'])->name('danh-muc-mon-an.export');
    Route::post('/import-danh-muc-mon-an', [DanhMucMonAnController::class, 'importDanhMucMonAn'])->name('danh-muc-mon-an.import');


    Route::resource('com-bo', ComBoController::class);
    Route::post('com-bo/restore/{id}', [ComBoController::class, 'restore'])->name('com-bo.restore');
    Route::get('export-com-bo', [ComBoController::class, 'export'])->name('com-bo.export');
    Route::post('/import-com-bo', [ComBoController::class, 'importComBo'])->name('com-bo.import');

    //Dịch vụ
    // Route::resource('dich-vu', DichVuController::class);
    // Route::post('dich-vu/restore/{id}', [DichVuController::class, 'restore'])->name('dich-vu.restore');
    // Route::get('export-dich-vu', [DichVuController::class, 'export'])->name('dich-vu.export');
    // Route::post('/import-dich-vu', [DichVuController::class, 'importDichVu'])->name('dich-vu.import');
    //Chức vụ
    Route::resource('chuc-vu', ChucVuController::class);
    Route::post('chuc-vu/restore/{id}', [ChucVuController::class, 'restore'])->name('chuc-vu.restore');
    Route::get('export-chuc-vu', [ChucVuController::class, 'export'])->name('chuc-vu.export');
    Route::post('/import-chuc-vu', [ChucVuController::class, 'importChucVu'])->name('chuc-vu.import');
    // Ca làm
    Route::resource('ca-lam', CaLamController::class);
    Route::post('ca-lam/restore/{id}', [CaLamController::class, 'restore'])->name('ca-lam.restore');
    Route::get('export-ca-lam', [CaLamController::class, 'export'])->name('ca-lam.export');
    Route::post('/import-ca-lam', [CaLamController::class, 'importCaLam'])->name('ca-lam.import');


    Route::resource('nha-cung-cap', \App\Http\Controllers\NhaCungCapController::class);
    Route::post('nha-cung-cap/restore/{id}', [\App\Http\Controllers\NhaCungCapController::class, 'restore'])->name('nha-cung-cap.restore');

    Route::get('export-nha-cung-cap', [\App\Http\Controllers\NhaCungCapController::class, 'export'])->name('nha-cung-cap.export');

    Route::post('/import-nha-cung-cap', [\App\Http\Controllers\NhaCungCapController::class, 'importNhaCungCap'])->name('nha-cung-cap.import');


    // Route::get('/', function () {
    //     return view('client.home');
    // });
    // chatbot
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/gui', [ChatController::class, 'guiTinNhan'])->name('chat.gui');
    Route::get('/chat/tin-nhan', [ChatController::class, 'layTinNhan'])->name('chat.layTinNhan');


    Route::post('/nha_cung_cap/import', [\App\Http\Controllers\NhaCungCapController::class, 'importNhaCungCap'])->name('nha_cung_cap.import');

    // Route::post('/nha_cung_cap/import', [\App\Http\Controllers\NhaCungCapController::class, 'importNhaCungCap'])->name('nha_cung_cap.import');


    // Phong an
    Route::get('/ban-an', [BanAnController::class, 'index'])->name('ban-an.index');
    Route::get('/ban-an/fetch', [BanAnController::class, 'fetchData'])->name('ban-an.fetch');

    // Route::resource('phong-an', PhongAnController::class);
    // Route::post('/phong-an/{banAn}/restore', [PhongAnController::class, 'restore'])->name('phong-an.restore');
    Route::get('ban-an/them-nhanh', [BanAnController::class, 'them'])->name('ban-an.themNhanh');
    Route::post('/ban-an/store-quick', [BanAnController::class, 'storeQuick'])->name('ban-an.store-quick');
    Route::resource('ban-an', BanAnController::class);
    Route::get('/ban-an/{id}', [BanAnController::class, 'show'])->name('ban-an.show');
    Route::post('/ban-an/{banAn}/restore', [BanAnController::class, 'restore'])->name('ban-an.restore');
    Route::get('/ban-an-export', [BanAnController::class, 'export'])->name('ban-an.export');

    Route::post('/ban-an/import', [BanAnController::class, 'importBanAn'])->name('ban-an.import');
    // Đặt bàn
    Route::get('/datban/ngay', [DatBanController::class, 'indexNgay']);
    Route::get('/api/datban', [DatBanController::class, 'getDatBanByDate']);


    // Route::post('/dat-ban', [DatBanController::class, 'datBan']);
    Route::get('/danh-sach-dat-ban', [DatBanController::class, 'DanhSach'])->name('datban.danhsach');


    Route::resource('dat-ban', DatBanController::class);
    // Route::get('/dat-ban/{dat_ban}/edit', [DatBanController::class, 'edit'])->name('dat-ban.edit');
    Route::get('/dat-ban/{maDatBan}', [DatBanController::class, 'show'])->name('dat-ban.show');

    Route::delete('/dat-ban/{ma_dat_ban}', [DatBanController::class, 'destroy'])->name('dat-ban.destroy');
    Route::post('/dat-ban/store', [DatBanController::class, 'store'])->name('dat-ban.store');
    Route::put('/dat-ban/{maDatBan}', [DatBanController::class, 'update'])->where('maDatBan', '[A-Za-z0-9]+')->name('dat-ban.update');

    Route::get('/dat-ban/edit/{maDatBan}', [DatBanController::class, 'edit'])->name('dat-ban.edit');
    Route::get('/list-dat-ban/{banId}', [DatBanController::class, 'getDatBanByBanId'])->name('datban.list');


    // Route để tìm kiếm khách hàng
    Route::get('/dat-ban/search-customer', [DatBanController::class, 'searchCustomer'])->name('admin.datban.search');
    Route::get('admin/khachhang/search', [DatBanController::class, 'searchCustomer'])->name('admin.khachhang.search');

    Route::get('admin/dat-ban/create', [DatBanController::class, 'create'])->name('admin.datban.create');
    Route::get('admin/khachhang/search', [DatBanController::class, 'searchCustomer'])->name('admin.khachhang.search');
    Route::get('/admin/datban/filter', [DatBanController::class, 'filterBanAnByTime'])->name('admin.datban.filter');
    // Route::get('/admin/datban/get-ban-an-for-edit', [DatBanController::class, 'getBanAnForEdit'])->name('admin.datban.getBanAnForEdit');

    Route::get('/filter-datban', [DatBanController::class, 'filterDatBan'])->name('datban.filter');
    Route::post('/table/booked/broadcast', [TableBookedController::class, 'broadcastTableBooking'])->name('table.booked.broadcast');

    Route::put('/dat-ban/{id}', [DatBanController::class, 'update'])->name('datban.update');
    // routes/web.php

    // Món ăn
    Route::resource('mon-an', MonAnController::class);
    Route::post('mon-an/restore/{id}', [MonAnController::class, 'restore'])->name('mon-an.restore');
    Route::get('export-mon-an', [MonAnController::class, 'exportMonAn'])->name('mon-an.export');

    Route::post('/import-mon-an', [MonAnController::class, 'importMonAn'])->name('mon-an.import');

    Route::delete('/mon-an/xoa-hinh-anh/{hinhAnhId}', [MonAnController::class, 'xoaHinhAnh'])->name('mon-an.xoa-hinh-anh');


    // Quản lí nhân viên
    Route::get('/nhan-vien', [NhanVienController::class, 'index'])->name('nhan-vien.index');
    Route::get('/nhan-vien/create', [NhanVienController::class, 'create'])->name('nhan-vien.create');
    Route::get('nhan-vien/{id}/', [NhanVienController::class, 'show'])->name('nhan-vien.detail');
    Route::post('/nhan-vien/store', [NhanVienController::class, 'store'])->name('nhan-vien.store');
    Route::get('/nhan-vien/edit/{id}', [NhanVienController::class, 'edit'])->name('nhan-vien.edit');
    Route::put('/nhan-vien/update/{id}', [NhanVienController::class, 'update'])->name('nhan-vien.update');
    Route::delete('/nhan-vien/destroy/{id}', [NhanVienController::class, 'destroy'])->name('nhan-vien.destroy');
    Route::post('nhan-vien/{id}/nghi-viec', [NhanVienController::class, 'nghiViec'])->name('nhan-vien.nghi-viec');
    Route::post('nhan-vien/{id}/khoi-phuc', [NhanVienController::class, 'khoiPhuc'])->name('nhan-vien.khoi-phuc');


    Route::get('/nhan-vien-export', [NhanVienController::class, 'exportNhanVien'])->name('nhan-vien.export');
    Route::post('/nhan-vien/import', [NhanVienController::class, 'importNhanVien'])->name('nhan-vien.import');

    Route::get('/bep', [BepController::class, 'index'])->name('bep.dashboard');
    Route::put('/bep/update/{id}', [BepController::class, 'updateTrangThai']);


    // kho
    //loại nguyên liệu
    Route::resource('loai-nguyen-lieu', LoaiNguyenLieuController::class);
    Route::post('loai-nguyen-lieu/{id}/restore', [LoaiNguyenLieuController::class, 'restore'])->name('loai-nguyen-lieu.restore');
    Route::get('export-loai-nguyen-lieu', [LoaiNguyenLieuController::class, 'export'])->name('loai-nguyen-lieu.export');
    Route::post('/import-loai-nguyen-lieu', [LoaiNguyenLieuController::class, 'import'])->name('loai-nguyen-lieu.import');

    // nguyên liệu 
    // Route::get('/nguyen-lieu/export', [NguyenLieuController::class, 'exportNguyenLieu']);
    // Route::post('/nguyen-lieu/import', [NguyenLieuController::class, 'importNguyenLieu']);
    Route::get('/nguyen-lieu/hansudung', [NguyenLieuController::class, 'HanSuDung'])->name('nguyen-lieu.hansudung');


    Route::get('/nguyen-lieu/kiem-tra-ton-kho', [NguyenLieuController::class, 'viewTonKhoXuatDung'])->name('nguyen-lieu.kiemtra');
    Route::resource('nguyen-lieu', NguyenLieuController::class);
    Route::post('nguyen-lieu/restore/{id}', [NguyenLieuController::class, 'restore'])->name('nguyen-lieu.restore');
    Route::get('export-nguyen-lieu', [NguyenLieuController::class, 'export'])->name('nguyen-lieu.export');
    Route::prefix('tools')->group(function () {
        Route::post('/import-nguyen-lieu', [NguyenLieuController::class, 'importNguyenLieu'])->name('tools.nguyen-lieu.import');
    });

    // nhập nkho
    Route::get('/phieu-nhap-kho/export', [PhieuNhapKhoController::class, 'exportDanhSach'])->name('phieu-nhap-kho.export');

    Route::resource('phieu-nhap-kho', PhieuNhapKhoController::class);
    Route::post('phieu-nhap-kho/restore/{id}', [PhieuNhapKhoController::class, 'restore'])->name('phieu-nhap-kho.restore');
    Route::put('phieu-nhap-kho/{id}/duyet', [PhieuNhapKhoController::class, 'duyet'])->name('phieu-nhap-kho.duyet');
    Route::put('/phieu-nhap-kho/{id}/huy', [PhieuNhapKhoController::class, 'huy'])->name('phieu-nhap-kho.huy');
    // Route riêng cho AJAX lấy chi tiết phiếu nhập
    Route::get('/phieu-nhap-kho/ajax-chi-tiet/{id}', [PhieuNhapKhoController::class, 'ajaxChiTiet']);

    // xuất kho
    Route::resource('phieu-xuat-kho', PhieuXuatKhoController::class);
    Route::post('phieu-xuat-kho/restore/{id}', [PhieuXuatKhoController::class, 'restore'])->name('phieu-xuat-kho.restore');
    Route::get('export-phieu-xuat-kho', [PhieuXuatKhoController::class, 'export'])->name('phieu-xuat-kho.export');
    Route::post('/import-phieu-xuat-kho', [PhieuXuatKhoController::class, 'importPhieuXuatKho'])->name('phieu-xuat-kho.import');
    Route::put('phieu-xuat-kho/{id}/duyet', [PhieuXuatKhoController::class, 'duyet'])->name('phieu-xuat-kho.duyet');
    Route::put('/phieu-xuat-kho/{id}/huy', [PhieuXuatKhoController::class, 'huy'])->name('phieu-xuat-kho.huy');
    ///Ca làm việc

    Route::prefix('ca-lam-nhan-vien')->group(function () {
        Route::get('/', [CaLamNhanVienController::class, 'index'])->name('ca-lam-nhan-vien.index'); // Hiển thị lịch làm việc
        Route::post('/store', [CaLamNhanVienController::class, 'store'])->name('ca-lam-nhan-vien.store'); // Thêm lịch làm việc
        // Xác nhận lịch làm việc

        Route::delete('/delete/{id}', [CaLamNhanVienController::class, 'delete'])->name('ca-lam-nhan-vien
    .delete'); // Xóa ca làm
        Route::get('/ca-lam-nhan-vien/export', [CaLamNhanVienController::class, 'export'])->name('ca-lam-nhan-vien.export');
        // Xuất file Excel

        Route::post('/dang-ky', [CaLamNhanVienController::class, 'dangKyCaLam'])->name('ca-lam-nhan-vien.dang-ky'); // Xử lý đăng ký ca làm

        Route::get('/ca-lam-nhan-vien
    /xin-nghi', [CaLamNhanVienController::class, 'showXinNghiForm'])->name('xinnghi.form');
        Route::post('/ca-lam-nhan-vien
    /xin-nghi', [CaLamNhanVienController::class, 'xinNghi'])->name('xinnghi.store');

        /////
        Route::get('/ca-lam-nhan-vien
    ', [CaLamNhanVienController::class, 'index'])->name('ca-lam-nhan-vien
    .index');
        Route::get('/ca-lam-nhan-vien
    /create', [CaLamNhanVienController::class, 'create'])->name('ca-lam-nhan-vien
    .create');
        Route::post('/ca-lam-nhan-vien
    ', [CaLamNhanVienController::class, 'store'])->name('ca-lam-nhan-vien
    .store');

        Route::resource('ca-lam-nhan-vien', CaLamNhanVienController::class);
        Route::put('ca-lam-nhan-vien/{id}', [CaLamNhanVienController::class, 'update'])
            ->name('ca-lam-nhan-vien.update');
        //xóa ca làm cho nhân viên
        Route::delete('/ca-lam-nhan-vien/{id}', [CaLamNhanVienController::class, 'destroy'])
            ->name('ca-lam-nhan-vien.destroy');
        //xin nghỉ cho nhân viên
        Route::get('/ca-lam-nhan-vien/xin-nghi', [CaLamNhanVienController::class, 'xinNghi'])
            ->name('ca-lam-nhan-vien.xin-nghi');
        // Route::post('/ca-lam-nhan-vien/xin-nghi', [CaLamNhanVienController::class, 'xinNghi'])
        // ->name('ca-lam-nhan-vien.xin-nghi');

        Route::delete('/ca-lam-nhan-vien/{id}', [CaLamNhanVienController::class, 'destroy'])
            ->name('ca-lam-nhan-vien.destroy');

        Route::get('/ca-lam-nhan-vien', [CaLamNhanVienController::class, 'index'])->name('ca-lam-nhan-vien.index');
    });

    ///Mã giảm giảm
    Route::resource('ma-giam-gia', MaGiamGiaController::class);
    Route::post('ma-giam-gia/restore/{id}', [MaGiamGiaController::class, 'restore'])->name('ma-giam-gia.restore');
    Route::get('export-ma-giam-gia', [MaGiamGiaController::class, 'export'])->name('ma-giam-gia.export');
    Route::post('/import-ma-giam-gia', [MaGiamGiaController::class, 'importMaGiamGia'])->name('ma-giam-gia.import');

    // thu ngân
    Route::get('/thu-ngan', [ThunganController::class, 'getBanAn'])->name('thungan.getBanAn');
    Route::get('/thu-ngan/get-thuc-don', [ThunganController::class, 'getThucDon'])->name('thungan.getThucDon');
    Route::post('/thu-ngan/tao-hoa-don', [HoaDonController::class, 'createHoaDon'])->name('thungan.createHoaDon');
    Route::get('/thu-ngan/hoa-don', [ThunganController::class, 'getHoaDon'])->name('thungan.getHoaDon');
    Route::get('/hoa-don/get-id', [ThuNganController::class, 'getHoaDonId'])->name('thungan.getHoaDonBan');
    Route::get('/hoa-don/get-details', [ThuNganController::class, 'getHoaDonDetails'])->name('thungan.getChiTietHoaDon');
    Route::delete('/thu-ngan/destroy/{id}', [ThuNganController::class, 'xoaHoaDon'])->name('thungan.destroy');
    Route::post('/hoa-don/update-status', [ThuNganController::class, 'updateStatus'])->name('thungan.thongBaoBep');
    Route::post('/update-ban-status', [ThuNganController::class, 'updateBanStatus'])->name('thungan.updateBanStatus');
    Route::post('/add-customer', [ThuNganController::class, 'addCustomer'])->name('thungan.addCustomer');
    Route::get('thu-ngan-get-ban', [ThuNganController::class, 'getBanDeGhep'])->name('thungan.getBanDeGhep');
    Route::get('thu-ngan-get-bill-ban/{id}', [ThuNganController::class, 'getBillBan'])
        ->name('thungan.getBillBan');
    Route::post('thu-ngan-ghep-ban', [ThuNganController::class, 'ghepBan'])
        ->name('thungan.ghepBan');
    Route::post('/hoa-don/update-quantity', [ThuNganController::class, 'updateQuantity'])->name('thungan.updateQuantity');
    Route::post('/hoa-don/delete', [ThuNganController::class, 'deleteMonAn'])->name('thungan.deleteMonAn');
    Route::get('/hoa-don', [HoaDonController::class, 'index'])->name('hoa-don.index');
    Route::get('/hoa-don/{id}', [HoaDonController::class, 'show'])->name('hoa-don.show');
    Route::get('/hoa-don/{id}/in', [HoaDonController::class, 'printInvoice'])->name('hoa-don.print');
    Route::get('/thu-ngan/get-orders', [ThuNganController::class, 'getOrders'])->name('thungan.getOrders');
    Route::get('/thu-ngan/hoa-don-info', [ThuNganController::class, 'thongTinHoaDon'])->name('thungan.thongTinHoaDon');
    Route::post('thu-ngan-save-so-nguoi', [ThuNganController::class, 'saveSoNguoi'])->name('thungan.saveSoNguoi');
    Route::get('/thu-ngan-thong-tin-don', [TachBanController::class, 'getDon'])->name('thungan.getDon');
    Route::post('/thu-ngan-tach-mon', [TachBanController::class, 'tachDon'])->name('thungan.tachDon');
    Route::post('/thu-ngan-xoa-hoa-don', [TachBanController::class, 'xoaHoaDonGoc'])->name('thungan.xoaHoaDon');
    Route::get('thu-ngan/hoa-don-thanh-toan', [ThuNganController::class, 'getHoaDonThanhToan'])->name('thungan.getHoaDonThanhToan');
    Route::post('thu-ngan/luu-ghi-chu-mon', [ThuNganController::class, 'saveNote'])->name('thungan.saveNote');
    Route::get('/thu-ngan/tao-qr/{ma}', [ThuNganController::class, 'taoQr']);
    Route::post('thu-ngan/apply-discount', [ThuNganController::class, 'applyDiscount'])->name('thungan.applyDiscount');
    Route::get('thu-ngan/upload-bill', [BillImageController::class, 'create'])->name('upload.bill.create');
    Route::post('thu-ngan/save-bill-image', [BillImageController::class, 'saveBillImage'])->name('upload.bill.save');
    Route::get('thu-ngan/get-id-from-ma', [ThuNganController::class, 'getIdFromMaHoaDon'])->name('hoa-don.get-id-from-ma');
    //Chấm công
    Route::get('/cham-cong', [ChamCongController::class, 'index'])->name('cham-cong.index');
    Route::post('/chamcong/store', [ChamCongController::class, 'store'])->name('chamcong.store');
    // Lấy dữ liệu chấm công để hiển thị trong modal


    Route::patch(
        '/cham-cong/update/{nhan_vien_id}/{ca}/{ngay}',
        [ChamCongController::class, 'updateChamCong']
    );
    //Kiểm cho chấm công
    Route::get('/cham-cong/check/{nhan_vien_id}/{ca}/{ngay}', [ChamCongController::class, 'checkChamCong']);
    Route::get('/lich-su-cham-cong', [ChamCongController::class, 'getLichSuChamCong']); //thay đổi tuần
    Route::get('/chamcong/change-week', [ChamCongController::class, 'changeWeek'])->name('chamcong.changeWeek');
    //xóa mềm chấm cống
    Route::post('/cham-cong/restore', [ChamCongController::class, 'restore'])->name('cham-cong.restore');
    Route::delete('/cham-cong/delete', [ChamCongController::class, 'softDelete'])->name('cham-cong.softDelete');
    Route::get('/cham-cong/danhsach', [ChamCongController::class, 'danhsach'])->name('cham-cong.danhsach');
    Route::resource('cham-cong', ChamCongController::class);
    Route::get('export-cham-cong', [ChamCongController::class, 'export'])->name('cham-cong.export');
    Route::post('/import-cham-cong', [ChamCongController::class, 'importDichVu'])->name('cham-cong.import');

    //Tính lương

    Route::get('/luong', [BangTinhLuongController::class, 'index'])->name('luong.index');
    Route::get('/luong/create', [BangTinhLuongController::class, 'create'])->name('luong.create');
    Route::post('/luong/store', [BangTinhLuongController::class, 'store'])->name('luong.store');

    Route::get('/luong/{id}', [BangTinhLuongController::class, 'show'])->name('luong.show');

    //lọc

    Route::get('/bangluong/filter', [BangTinhLuongController::class, 'filter'])->name('bangluong.filter');
    Route::get('/bang-luong-export', [BangTinhLuongController::class, 'exportBangLuong'])->name('bang-luong.export');
    Route::post('luong/import', [BangTinhLuongController::class, 'BangLuongImport'])->name('bang-luong.import');


    //Thống kê
    Route::get('/thong-ke-mon-an', [ThongKeMonAnController::class, 'thongKeMonAn'])->name('thongke.thongkemonan');


    Route::get('/bep', [BepController::class, 'index'])->name('bep.dashboard');

    Route::get('/test-log', function () {
        Log::info('Test ghi log Laravel');
        return 'Đã ghi log!';
    });
    // Thống kê số lượng bàn
    Route::get('/thong-ke-so-ban', [ThongKeSoBanController::class, 'index'])->name('thongke.thongkesoban');

    Route::get('/get-dat-ban-by-date', function (Request $request) {
        $date = $request->input('date', Carbon::now('Asia/Ho_Chi_Minh')->toDateString());

        // Lấy danh sách bàn
        $banAns = BanAn::whereNull('deleted_at')->get();

        // Lấy danh sách đặt bàn theo ngày
        $datBans = DatBan::whereDate('thoi_gian_den', $date)
            ->whereIn('ban_an_id', $banAns->pluck('id'))
            ->whereNull('deleted_at')
            ->get();

        return response()->json([
            'banAns' => $banAns,
            'datBans' => $datBans
        ]);
    });
});
