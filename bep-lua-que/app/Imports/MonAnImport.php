<?php

namespace App\Imports;

use App\Models\DanhMucMonAn;
use App\Models\MonAn;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MonAnImport implements ToModel, WithHeadingRow
{
    public function headingRow(): int
    {
        return 2; // Bắt đầu từ dòng 2
    }

    public function model(array $row)
    {
        return new MonAn([
            'danh_muc_mon_an_id' => DanhMucMonAn::where('ten', $row['danh_muc_mon'])->value('id') ?? null,
            'ten' => $row['ten_mon'] ?? '',
            'mo_ta' => $row['mo_ta'] ?? '',
            'gia' => $this->convertGia($row['gia']),
            'trang_thai' => $this->mapTrangThai($row['trang_thai'] ?? ''),
            'thoi_gian_nau' => !empty($row['thoi_gian_nau']) ? (int) $row['thoi_gian_nau'] : 0,
            'created_at' => $row['created_at'] ?? null,
            'deleted_at' => $row['deleted_at'] ?? null,
        ]);
    }

    private function mapTrangThai($trangThai)
    {
        return match (strtolower(trim($trangThai))) {
            'đang bán', 'dang ban' => 'dang_ban',
            'ngừng bán', 'ngung ban' => 'ngung_ban',
            'hết hàng', 'het hang' => 'het_hang',
            default => 'dang_ban',
        };
    }

    private function convertGia($gia)
    {
        $gia = preg_replace('/[^0-9,.]/', '', $gia);
        // / Xóa dấu . và dấu , để tránh hiểu sai phần thập phân
    $gia = str_replace(['.', ','], '', $gia);

        return is_numeric($gia) ? (float) $gia : 0;
    }
}

