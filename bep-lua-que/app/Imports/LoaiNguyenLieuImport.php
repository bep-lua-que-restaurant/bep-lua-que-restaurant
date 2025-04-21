<?php

namespace App\Imports;

use App\Models\LoaiNguyenLieu;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LoaiNguyenLieuImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new LoaiNguyenLieu([
            'ten_loai' => $row['ten_loai'],
            'ghi_chu' => $row['ghi_chu'] ?? null,
        ]);
    }
}

