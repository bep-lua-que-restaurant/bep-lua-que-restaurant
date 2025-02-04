<?php

use App\Http\Controllers\CaLamController;
use App\Http\Controllers\DanhMucMonAnController;
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

// Ca làm
Route::resource('ca-lam', CaLamController::class);
Route::post('ca-lam/restore/{id}', [CaLamController::class, 'restore'])->name('ca-lam.restore');
Route::get('export-ca-lam', [CaLamController::class, 'export'])->name('ca-lam.export');
Route::post('/import-ca-lam', [CaLamController::class, 'importDanhMucMonAn'])->name('ca-lam.import');
// Route::get('/', function () {
//     return view('client.home');
// });