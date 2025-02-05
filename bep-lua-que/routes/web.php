<?php

use App\Http\Controllers\ChiTietNhapKhoController;
use App\Http\Controllers\MonAnController;
use App\Http\Controllers\NguyenLieuController;
use App\Http\Controllers\PhieuNhapKhoController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BepController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuanLyController;
use App\Http\Controllers\ThuNganController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\DanhMucMonAnController;

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


Route::resource('nha-cung-cap', \App\Http\Controllers\NhaCungCapController::class);
Route::post('nha-cung-cap/restore/{id}', [\App\Http\Controllers\NhaCungCapController::class, 'restore'])->name('nha-cung-cap.restore');
Route::get('export-nha-cung-cap', [\App\Http\Controllers\NhaCungCapController::class, 'export'])->name('nha-cung-cap.export');
//Route::post('/import-nha-cung-cap', [\App\Http\Controllers\NhaCungCapController::class, 'importNhaCungCap'])->name('nha-cung-cap.import');

// Route::get('/', function () {
//     return view('client.home');
// });

// Món ăn
Route::resource('mon-an', MonAnController::class);
Route::post('mon-an/restore/{id}', [MonAnController::class, 'restore'])->name('mon-an.restore');
Route::get('export-mon-an', [MonAnController::class, 'exportMonAn'])->name('mon-an.export');
Route::post('/import-mon-an', [MonAnController::class, 'importMonAn'])->name('mon-an.import');
Route::delete('/mon-an/xoa-hinh-anh/{id}', [MonAnController::class, 'xoaHinhAnh'])->name('mon-an.xoa-hinh-anh');

// phiếu nhập nguyên liệu
Route::resource('phieu-nhap-kho', PhieuNhapKhoController::class);
Route::post('/restore/{id}', [PhieuNhapKhoController::class, 'restore'])->name('phieu-nhap-kho.restore'); // Khôi phục phiếu nhập
Route::get('export-phieu-nhap-kho', [PhieuNhapKhoController::class, 'exportPhieuNhapKho'])->name('phieu-nhap-kho.export');








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



// Đăng nhập phân quyền
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/bep', [BepController::class, 'index'])->name('bep.dashboard');
    Route::get('/thu-ngan', [ThuNganController::class, 'index'])->name('thungan.dashboard');
    // Route::get('/quan-li', [QuanLyController::class, 'index'])->name('admin.dashboard');
});


