<?php

use App\Http\Controllers\BanAnController;
use App\Http\Controllers\DanhMucMonAnController;
use App\Http\Controllers\DatBanController;
use App\Http\Controllers\PhongAnController;
use App\Models\PhongAn;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TableBookedController;


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

// Phong an 
Route::resource('phong-an', PhongAnController::class);
Route::post('/phong-an/{banAn}/restore', [PhongAnController::class, 'restore'])->name('phong-an.restore');
//Phong an

// Bàn ăn 
Route::resource('ban-an', BanAnController::class);
Route::get('/ban-an/{id}', [BanAnController::class, 'show'])->name('ban-an.show');
Route::post('/ban-an/{banAn}/restore', [BanAnController::class, 'restore'])->name('ban-an.restore');
Route::get('/ban-an-export', [BanAnController::class, 'export'])->name('ban-an.export');
Route::post('/ban-an/import', [BanAnController::class, 'import'])->name('ban-an.import');
// Bàn ăn

// Đặt bàn
Route::resource('dat-ban', DatBanController::class);


// Route để tìm kiếm khách hàng
Route::get('/dat-ban/search-customer', [DatBanController::class, 'searchCustomer'])->name('admin.datban.search');
Route::get('admin/khachhang/search', [DatBanController::class, 'searchCustomer'])->name('admin.khachhang.search');


Route::get('admin/dat-ban/create', [DatBanController::class, 'create'])->name('admin.datban.create');
Route::get('admin/khachhang/search', [DatBanController::class, 'searchCustomer'])->name('admin.khachhang.search');

Route::get('/admin/datban/filter', [DatBanController::class, 'filterBanAnByTime'])->name('admin.datban.filter');

Route::get('/filter-datban', [DatBanController::class, 'filterDatBan'])->name('datban.filter');

Route::post('/table/booked/broadcast', [TableBookedController::class, 'broadcastTableBooking'])->name('table.booked.broadcast');
