<?php


use App\Http\Controllers\BangTinhLuongController;
use App\Http\Controllers\ChamCongController;
use App\Http\Controllers\DanhMucMonAnController;


use App\Http\Controllers\ComBoController;
use App\Http\Controllers\DichVuController;
use App\Http\Controllers\BanAnController;
use App\Http\Controllers\DatBanController;
use App\Http\Controllers\LoaiNguyenLieuController;
use App\Http\Controllers\PhongAnController;
use App\Http\Controllers\LuongController;

use Illuminate\Support\Facades\Route;

use App\Models\PhongAn;
// use Illuminate\Support\Facades\Route;  // Dòng này đã bị xóa

use App\Http\Controllers\TableBookedController;
use App\Http\Controllers\CaLamController;
use App\Http\Controllers\ChiTietNhapKhoController;
use App\Http\Controllers\MonAnController;
use App\Http\Controllers\NguyenLieuController;
// use App\Http\Controllers\PhieuNhapKhoController;


// use App\Http\Controllers\PhieuNhapKhoController;
 // Giữ lại một lần duy nhất

use App\Http\Controllers\BepController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\QuanLyController;
use App\Http\Controllers\ThuNganController;
use App\Http\Controllers\NhanVienController;

use App\Http\Controllers\ThongKeController;

use App\Http\Controllers\PhieuNhapKhoController;

use App\Http\Controllers\LichLamViecController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('admin.dashboard');
});


// Danh mục món ăn
Route::resource('danh-muc-mon-an', DanhMucMonAnController::class);
Route::post('danh-muc-mon-an/restore/{id}', [DanhMucMonAnController::class, 'restore'])->name('danh-muc-mon-an.restore');
Route::get('export-danh-muc-mon-an', [DanhMucMonAnController::class, 'export'])->name('danh-muc-mon-an.export');
Route::post('/import-danh-muc-mon-an', [DanhMucMonAnController::class, 'importDanhMucMonAn'])->name('danh-muc-mon-an.import');




Route::resource('com-bo', ComBoController::class);
Route::post('com-bo/restore/{id}', [ComBoController::class, 'restore'])->name('com-bo.restore');
Route::get('export-com-bo', [ComBoController::class, 'export'])->name('com-bo.export');
Route::post('/import-com-bo', [ComBoController::class, 'importComBo'])->name('com-bo.import');





//Dịch vụ
Route::resource('dich-vu', DichVuController::class);
Route::post('dich-vu/restore/{id}', [DichVuController::class, 'restore'])->name('dich-vu.restore');
Route::get('export-dich-vu', [DichVuController::class, 'export'])->name('dich-vu.export');
Route::post('/import-dich-vu', [DichVuController::class, 'importDichVu'])->name('dich-vu.import');

// Ca làm
Route::resource('ca-lam', CaLamController::class);
Route::post('ca-lam/restore/{id}', [CaLamController::class, 'restore'])->name('ca-lam.restore');
Route::get('export-ca-lam', [CaLamController::class, 'export'])->name('ca-lam.export');
Route::post('/import-ca-lam', [CaLamController::class, 'importDanhMucMonAn'])->name('ca-lam.import');

Route::resource('nha-cung-cap', \App\Http\Controllers\NhaCungCapController::class);
Route::post('nha-cung-cap/restore/{id}', [\App\Http\Controllers\NhaCungCapController::class, 'restore'])->name('nha-cung-cap.restore');
Route::get('export-nha-cung-cap', [\App\Http\Controllers\NhaCungCapController::class, 'export'])->name('nha-cung-cap.export');
//Route::post('/import-nha-cung-cap', [\App\Http\Controllers\NhaCungCapController::class, 'importNhaCungCap'])->name('nha-cung-cap.import');


// Route::get('/', function () {
//     return view('client.home');
// });


// Phong an
Route::resource('phong-an', PhongAnController::class);
Route::post('/phong-an/{banAn}/restore', [PhongAnController::class, 'restore'])->name('phong-an.restore');
//Phong an


Route::resource('ban-an', BanAnController::class);
Route::get('/ban-an/{id}', [BanAnController::class, 'show'])->name('ban-an.show');
Route::post('/ban-an/{banAn}/restore', [BanAnController::class, 'restore'])->name('ban-an.restore');
Route::get('/ban-an-export', [BanAnController::class, 'export'])->name('ban-an.export');
Route::post('/ban-an/import', [BanAnController::class, 'import'])->name('ban-an.import');


// Bàn ăn

// Đặt bàn
Route::resource('dat-ban', DatBanController::class);
Route::get('/dat-ban/{dat_ban}/edit', [DatBanController::class, 'edit'])->name('dat-ban.edit');


// Route để tìm kiếm khách hàng
Route::get('/dat-ban/search-customer', [DatBanController::class, 'searchCustomer'])->name('admin.datban.search');
Route::get('admin/khachhang/search', [DatBanController::class, 'searchCustomer'])->name('admin.khachhang.search');


Route::get('admin/dat-ban/create', [DatBanController::class, 'create'])->name('admin.datban.create');
Route::get('admin/khachhang/search', [DatBanController::class, 'searchCustomer'])->name('admin.khachhang.search');
Route::get('/admin/datban/filter', [DatBanController::class, 'filterBanAnByTime'])->name('admin.datban.filter');
Route::get('/admin/datban/get-ban-an-for-edit', [DatBanController::class, 'getBanAnForEdit'])->name('admin.datban.getBanAnForEdit');

Route::get('/filter-datban', [DatBanController::class, 'filterDatBan'])->name('datban.filter');
Route::post('/table/booked/broadcast', [TableBookedController::class, 'broadcastTableBooking'])->name('table.booked.broadcast');

// routes/web.php

Route::put('/dat-ban/{id}', [DatBanController::class, 'update'])->name('datban.update');

// Món ăn
Route::resource('mon-an', MonAnController::class);
Route::post('mon-an/restore/{id}', [MonAnController::class, 'restore'])->name('mon-an.restore');
Route::get('export-mon-an', [MonAnController::class, 'exportMonAn'])->name('mon-an.export');
Route::post('/import-mon-an', [MonAnController::class, 'importMonAn'])->name('mon-an.import');
Route::delete('/mon-an/xoa-hinh-anh/{hinhAnhId}', [MonAnController::class, 'xoaHinhAnh'])->name('mon-an.xoa-hinh-anh');

// loại nguyên liêu
Route::resource('loai-nguyen-lieu', LoaiNguyenLieuController::class);
Route::get('export-loai-nguyen-lieu', [LoaiNguyenLieuController::class, 'export'])->name('loai-nguyen-lieu.export');
Route::post('loai-nguyen-lieu/{id}/restore', [LoaiNguyenLieuController::class, 'restore'])->name('loai-nguyen-lieu.restore');

// // phiếu nhập nguyên liệu
Route::resource('phieu-nhap-kho', PhieuNhapKhoController::class);
Route::post('/restore/{id}', [PhieuNhapKhoController::class, 'restore'])->name('phieu-nhap-kho.restore'); // Khôi phục phiếu nhập
Route::get('export-phieu-nhap-kho', [PhieuNhapKhoController::class, 'exportPhieuNhapKho'])->name('phieu-nhap-kho.export');
Route::put('/phieu-nhap-kho/{id}/duyet', [PhieuNhapKhoController::class, 'duyet'])->name('phieu-nhap-kho.duyet');
Route::put('/phieu-nhap-kho/{id}/huy', [PhieuNhapKhoController::class, 'huy'])->name('phieu-nhap-kho.huy');
// Route để xem chi tiết nguyên liệu
Route::get('phieu-nhap-kho/{phieuNhapId}/nguyen-lieu/{nguyenLieuId}', 
    [PhieuNhapKhoController::class, 'xemChiTietNguyenLieu'])
    ->name('phieu-nhap-kho.chitiet-nguyenlieu');



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



Route::get('/bep', [BepController::class, 'index'])->name('bep.dashboard');
Route::put('/bep/update/{id}', [BepController::class, 'updateTrangThai']);
Route::get('/quan-li', [QuanLyController::class, 'index'])->name('admin.dashboard');





// // Đăng nhập phân quyền
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/bep', [BepController::class, 'index'])->name('bep.dashboard');
    Route::put('/bep/update/{id}', [BepController::class, 'updateTrangThai']);
    Route::get('/quan-li', [QuanLyController::class, 'index'])->name('admin.dashboard');
});
Route::get('/quan-li', [QuanLyController::class, 'index'])->name('admin.dashboard');




//lịch làm việc

Route::resource('lich-lam-viec', LichLamViecController::class);
Route::get('lich-lam-viec/export', [LichLamViecController::class, 'export'])->name('lich-lam-viec.export');


Route::get('/thu-ngan', [ThunganController::class, 'getBanAn'])->name('thungan.getBanAn');
Route::get('/thu-ngan/get-thuc-don', [ThunganController::class, 'getThucDon'])->name('thungan.getThucDon');
Route::post('/thu-ngan/tao-hoa-don', [HoaDonController::class, 'createHoaDon'])->name('thungan.createHoaDon');
Route::get('/thu-ngan/hoa-don', [ThunganController::class, 'getHoaDon'])->name('thungan.getHoaDon');
Route::get('/hoa-don/get-id', [ThuNganController::class, 'getHoaDonId'])->name('thungan.getHoaDonBan');
Route::get('/hoa-don/get-details', [ThuNganController::class, 'getHoaDonDetails'])->name('thungan.getChiTietHoaDon');




Route::get('/', [ThongKeController::class, 'index'])->name('dashboard');





Route::delete('/thu-ngan/destroy/{id}', [ThuNganController::class, 'xoaHoaDon'])->name('thungan.destroy');



Route::delete('/thu-ngan/destroy/{id}', [ThuNganController::class, 'xoaHoaDon'])->name('thungan.destroy');
Route::post('/hoa-don/update-status', [ThuNganController::class, 'updateStatus'])->name('thungan.thongBaoBep');
Route::post('/update-ban-status', [ThuNganController::class, 'updateBanStatus'])->name('thungan.updateBanStatus');
Route::post('/add-customer', [ThuNganController::class, 'addCustomer'])->name('thungan.addCustomer');
Route::get('thu-ngan-get-ban', [ThuNganController::class, 'getBanDeGhep'])->name('thungan.getBanDeGhep');
Route::get('thu-ngan-get-bill-ban/{id}', [ThuNganController::class, 'getBillBan'])
    ->name('thungan.getBillBan');
Route::post('thu-ngan-ghep-ban', [ThuNganController::class, 'ghepBan'])
    ->name('thungan.ghepBan');

Route::get('/hoa-don', [HoaDonController::class, 'index'])->name('hoa-don.index');
Route::get('/hoa-don/{id}', [HoaDonController::class, 'show'])->name('hoa-don.show');

Route::get('/hoa-don/search', [HoaDonController::class, 'search'])->name('hoa-don.search');


Route::get('/hoa-don/search',[HoaDonController::class, 'search'])->name('hoa-don.search');

//Chấm công

Route::get('/cham-cong', [ChamCongController::class, 'index'])->name('cham-cong.index');

Route::post('/chamcong/store', [ChamCongController::class, 'store'])->name('chamcong.store');

// Lấy dữ liệu chấm công để hiển thị trong modal
Route::get('/cham-cong/edit/{nhanVienId}/{ca}/{ngay}', [ChamCongController::class, 'edit']);

//Update chấm công
Route::patch('/cham-cong/update/{nhan_vien_id}/{ca}/{ngay}', 
    [ChamCongController::class, 'updateChamCong']
);

//Kiểm cho chấm công
Route::get('/cham-cong/check/{nhan_vien_id}/{ca}/{ngay}', [ChamCongController::class, 'checkChamCong']);

Route::get('/lich-su-cham-cong', [ChamCongController::class, 'getLichSuChamCong']);
//thay đổi tuần
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
Route::get('/bang-luong/filter', [BangTinhLuongController::class, 'filter'])->name('bang-luong.filter');


Route::get('luong/export', [BangTinhLuongController::class, 'export'])->name('luong.export');
Route::post('luong/import', [BangTinhLuongController::class, 'import'])->name('luong.import');





Route::get('/hoa-don/search', [HoaDonController::class, 'search'])->name('hoa-don.search');

// Đăng nhập phân quyền
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/bep', [BepController::class, 'index'])->name('bep.dashboard');
    Route::get('/thu-ngan', [ThuNganController::class, 'index'])->name('thungan.dashboard');
    // Route::get('/quan-li', [QuanLyController::class, 'index'])->name('admin.dashboard');
});

Route::get('/hoa-don/search',[HoaDonController::class, 'search'])->name('hoa-don.search');





