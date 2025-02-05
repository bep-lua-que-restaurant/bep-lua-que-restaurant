<?php

use App\Http\Controllers\DanhMucMonAnController;
use App\Http\Controllers\DichVuController;
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

//Dịch vụ
Route::resource('dich-vu', DichVuController::class);
Route::post('dich-vu/restore/{id}', [DichVuController::class, 'restore'])->name('dich-vu.restore');
Route::get('export-dich-vu', [DichVuController::class, 'export'])->name('dich-vu.export');
Route::post('/import-dich-vu', [DichVuController::class, 'importDichVu'])->name('dich-vu.import');
// Route::get('/', function () {
//     return view('client.home');
// });