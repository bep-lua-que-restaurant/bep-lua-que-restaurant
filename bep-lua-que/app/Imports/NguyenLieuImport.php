<?php

namespace App\Imports;

use App\Models\LoaiNguyenLieu;
use App\Models\NguyenLieu;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NguyenLieuImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $tenLoai = trim($row['ten_loai_nguyen_lieu']);

        $loai = LoaiNguyenLieu::where('ten_loai', $tenLoai)->first();

        // Nếu loại không tồn tại thì ném lỗi và ghi rõ dòng
        if (!$loai) {
            throw ValidationException::withMessages([
                'ten_loai_nguyen_lieu' => "Loại nguyên liệu '$tenLoai' không tồn tại trong hệ thống. Vui lòng kiểm tra lại (dòng chứa '{$row['ten_nguyen_lieu']}')"
            ]);
        }

        return new NguyenLieu([
            'ten_nguyen_lieu'     => $row['ten_nguyen_lieu'],
            'loai_nguyen_lieu_id' => $loai->id,
            'don_vi_ton'          => $row['don_vi_ton'],
            'don_gia'             => $row['don_gia'],
            'so_luong_ton'        => $row['so_luong_ton'],
            'ghi_chu'             => $row['ghi_chu'],
        ]);
    }
}

