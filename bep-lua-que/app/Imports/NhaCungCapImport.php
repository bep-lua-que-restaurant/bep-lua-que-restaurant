<?php

namespace App\Imports;

use App\Models\NhaCungCap;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NhaCungCapImport implements OnEachRow, WithHeadingRow
{
    public array $errors = [];

    public function headingRow(): int
    {
        return 2;
    }

    public function onRow(Row $row)
    {
        $data = $row->toArray();
        $rowIndex = $row->getIndex();

        // Validate thủ công các cột cần thiết
        // Tạm ẩn lỗi chi tiết và gom lại
        $validator = Validator::make($data, [
            'ten' => 'required|string|unique:nha_cung_caps,ten_nha_cung_cap',
            'so_dien_thoai' => 'required|string|unique:nha_cung_caps,so_dien_thoai',
            'email' => 'required|email|unique:nha_cung_caps,email',
        ]);

        if ($validator->fails()) {
            $this->errors[] = "Lỗi dữ liệu bị trùng hoặc không hợp lệ.";
            return; // Không lưu dòng này
        }

        // Nếu hợp lệ thì thêm dữ liệu
        NhaCungCap::create([
            'ten_nha_cung_cap' => $data['ten'],
            'dia_chi' => $data['dia_chi'] ?? null,
            'so_dien_thoai' => $data['so_dien_thoai'],
            'email' => $data['email'],
            'moTa' => $data['mo_ta'] ?? $data['Mô tả'] ?? null,
        ]);
    }
}
