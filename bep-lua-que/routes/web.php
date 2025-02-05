<?php

use App\Http\Controllers\ChiTietNhapKhoController;
use App\Http\Controllers\DanhMucMonAnController;
use App\Http\Controllers\MonAnController;
use App\Http\Controllers\NguyenLieuController;
use App\Http\Controllers\PhieuNhapKhoController;
use Illuminate\Support\Facades\Route;

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






