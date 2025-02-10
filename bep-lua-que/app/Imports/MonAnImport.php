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
        try {
            // Chuyển tất cả key về chữ thường để tránh lỗi key không khớp
            $row = array_change_key_case($row, CASE_LOWER);

            // Lấy dữ liệu từ file Excel
            $ten = trim($row['tên'] ?? '');
            $moTa = trim($row['mô tả'] ?? '');
            $gia = isset($row['giá']) ? (float) str_replace(',', '', trim($row['giá'])) : null;
            $tenDanhMuc = trim($row['danh mục'] ?? '');
            $trangThai = strtolower(str_replace(' ', '_', trim($row['trạng thái'] ?? 'dang_ban')));
            $ngayTao = trim($row['ngày tạo'] ?? '');
            $trangThaiKinhDoanh = strtolower(trim($row['trạng thái kinh doanh'] ?? ''));

            // Kiểm tra danh mục món ăn
            $danhMuc = DanhMucMonAn::where('ten', $tenDanhMuc)->first();
            if (!$danhMuc) {
                Log::error("Danh mục không tồn tại: " . $tenDanhMuc);
                return null;
            }

            // Kiểm tra nếu dữ liệu bị thiếu
            if (!$ten || $gia === null) {
                Log::error("Lỗi dữ liệu thiếu hoặc sai: " . json_encode($row));
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

            // Bắt đầu giao dịch
            DB::beginTransaction();

            // Kiểm tra nếu món ăn đã tồn tại
            $monAnExist = MonAn::withTrashed()->where('ten', $ten)->where('danh_muc_mon_an_id', $danhMuc->id)->first();

            if ($monAnExist) {
                // Nếu món ăn tồn tại, cập nhật dữ liệu thay vì bỏ qua
                $monAnExist->update([
                    'mo_ta' => $moTa,
                    'gia' => $gia,
                    'trang_thai' => $trangThai,
                    'deleted_at' => $deletedAt, // Nếu ngừng kinh doanh, cập nhật deleted_at
                ]);

                if ($deletedAt === null && $monAnExist->trashed()) {
                    $monAnExist->restore(); // Khôi phục nếu trước đó bị soft delete
                }

                Log::info("Món ăn đã tồn tại, cập nhật dữ liệu: " . $ten);
                DB::commit();
                return $monAnExist;
            } else {
                // Nếu chưa tồn tại, tạo mới
                $monAn = MonAn::create([
                    'ten' => $ten,
                    'mo_ta' => $moTa,
                    'gia' => $gia,
                    'danh_muc_mon_an_id' => $danhMuc->id,
                    'trang_thai' => $trangThai,
                    'created_at' => $createdAt,
                    'deleted_at' => $deletedAt,
                ]);

                Log::info("Tạo mới món ăn thành công: " . $ten);
                DB::commit();
                return $monAn;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi khi xử lý món ăn: " . $e->getMessage());
            return null;
        }
    }
}
