<?php

use App\Http\Controllers\DanhMucMonAnController;
use App\Http\Controllers\ComBoController;
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

Route::resource('com-bo', ComBoController::class);
Route::post('com-bo/restore/{id}', [ComBoController::class, 'restore'])->name('com-bo.restore');
Route::get('export-com-bo', [ComBoController::class, 'export'])->name('com-bo.export');
Route::post('/import-com-bo', [ComBoController::class, 'importComBo'])->name('com-bo.import');



// Route::get('/', function () {
//     return view('client.home');
// });