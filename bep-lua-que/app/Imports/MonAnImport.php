<?php

namespace App\Imports;

use App\Models\MonAn;
use App\Models\DanhMucMonAn;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MonAnImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Chuyển tất cả key về chữ thường để tránh lỗi key không khớp
        $row = array_change_key_case($row, CASE_LOWER);

        // Kiểm tra và lấy dữ liệu từ file Excel
        $ten = trim($row['tên'] ?? '');
        $moTa = trim($row['mô tả'] ?? '');
        $gia = isset($row['giá']) ? (float) str_replace(',', '', trim($row['giá'])) : null;
        $tenDanhMuc = trim($row['danh mục'] ?? '');
        $trangThai = strtolower(str_replace(' ', '_', trim($row['trạng thái'] ?? 'dang_ban')));
        $ngayTao = trim($row['ngày tạo'] ?? '');
        $trangThaiKinhDoanh = strtolower(trim($row['trạng thái kinh doanh'] ?? ''));

        // Kiểm tra danh mục món ăn
        $danhMuc = DanhMucMonAn::where('ten', $tenDanhMuc)->first();

        // Nếu danh mục không tồn tại, log lỗi và bỏ qua dòng này
        if (!$danhMuc) {
            Log::error("Danh mục không tồn tại: " . $tenDanhMuc);
            return null;
        }

        // Kiểm tra nếu dữ liệu bị thiếu
        if (!$ten || !$gia) {
            Log::error("Lỗi dữ liệu thiếu hoặc sai: " . json_encode($row));
            return null;
        }

        // Kiểm tra nếu món ăn đã tồn tại
        $monAnExist = MonAn::where('ten', $ten)->where('danh_muc_mon_an_id', $danhMuc->id)->first();
        if ($monAnExist) {
            Log::warning("Món ăn đã tồn tại: " . $ten);
            return null;
        }

        // Xử lý ngày tạo
        try {
            $createdAt = $ngayTao ? Carbon::createFromFormat('d/m/Y', $ngayTao)->format('Y-m-d') : now()->format('Y-m-d');
        } catch (\Exception $e) {
            Log::error("Lỗi định dạng ngày tạo: " . $ngayTao);
            return null;
        }

        // Kiểm tra trạng thái kinh doanh
        $deletedAt = ($trangThaiKinhDoanh === 'ngừng kinh doanh') ? now() : null;

        // Ghi log dữ liệu trước khi lưu
        Log::info("Importing MonAn: ", [
            'ten' => $ten,
            'mo_ta' => $moTa,
            'gia' => $gia,
            'danh_muc_mon_an_id' => $danhMuc->id,
            'trang_thai' => $trangThai,
            'created_at' => $createdAt,
            'deleted_at' => $deletedAt,
        ]);

        // Lưu dữ liệu vào database
        try {
            DB::beginTransaction();

            $monAn = MonAn::create([
                'ten' => $ten,
                'mo_ta' => $moTa,
                'gia' => $gia,
                'danh_muc_mon_an_id' => $danhMuc->id,
                'trang_thai' => $trangThai,
                'created_at' => $createdAt,
                'deleted_at' => $deletedAt,
            ]);

            DB::commit();
            return $monAn;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi khi lưu món ăn: " . $e->getMessage());
            return null;
        }
    }
}
