<?php



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BepController;
use App\Http\Controllers\ChamCongController;

use App\Http\Controllers\BanAnController;


use App\Http\Controllers\ChatController;

use App\Http\Controllers\DatBanController;
use App\Models\DatBan;
use Carbon\Carbon;
use App\Models\BanAn;



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


Route::get('/chat/tin-nhan', [ChatController::class, 'layTinNhan']);
Route::post('/chat/gui', [ChatController::class, 'guiTinNhan']);



Route::get('/update-datban', function () {
    $now = Carbon::now();
    $limitTime = $now->subMinutes(30);

    DatBan::where('trang_thai', 'dang_xu_ly')
        ->where('thoi_gian_den', '<=', $limitTime)
        ->update(['trang_thai' => 'xac_nhan']);

    return response()->json(['message' => 'Cập nhật thành công']);
});

Route::get('/api/datban', [DatBanController::class, 'getDatBanByDate']);

Route::get('/datban', function (Request $request) {
    $date = $request->query('date', Carbon::today()->toDateString());
    $today = Carbon::parse($date);

    // Lấy danh sách bàn
    $banPhong = BanAn::whereNull('deleted_at')->orderBy('vi_tri')->orderBy('id')->get();

    // Lấy danh sách đơn đặt bàn theo ngày
    $datBansToday = DatBan::whereDate('thoi_gian_den', $today)
        ->whereIn('ban_an_id', $banPhong->pluck('id'))
        ->whereNull('deleted_at')
        ->get();

    return response()->json([
        'banPhong' => $banPhong,
        'datBans' => $datBansToday,
    ]);
});


Route::get('/datban/{maDatBan}', [DatBanController::class, 'getDatBan']);
