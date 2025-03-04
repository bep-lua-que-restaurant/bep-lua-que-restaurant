<?php

use App\Http\Controllers\ChamCongController;

use App\Http\Controllers\BanAnController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BepController;

use App\Models\DatBan;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/them-mon', [BepController::class, 'themMon']);


Route::post('/chamcong/store', [ChamCongController::class, 'store'])->name('chamcong.store');

Route::get('/get-so-luong-ban', [BanAnController::class, 'getSoLuongBan']);



Route::get('/update-datban', function () {
    $now = Carbon::now();
    $limitTime = $now->subMinutes(30);

    DatBan::where('trang_thai', 'dang_xu_ly')
        ->where('thoi_gian_den', '<=', $limitTime)
        ->update(['trang_thai' => 'xac_nhan']);

    return response()->json(['message' => 'Cập nhật thành công']);
});
