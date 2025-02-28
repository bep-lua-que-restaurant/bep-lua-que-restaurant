<?php

namespace App\Imports;

use App\Models\MaGiamGia;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Nếu file import có tiêu đề cột

class MaGiamGiaImport implements ToModel, WithHeadingRow
{
    /**
     * Phương thức model() sẽ được gọi cho mỗi hàng dữ liệu.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new MaGiamGia([
            'code'            => $row['code'], // Tên cột trong file Excel phải là code
            'type'            => $row['type'], // Tên cột trong file Excel phải là type
            'value'           => $row['value'],
            'min_order_value' => $row['min_order_value'] ?? 0,
            'start_date'      => $row['start_date'],
            'end_date'        => $row['end_date'],
            'usage_limit'     => $row['usage_limit'] ?? 0,
        ]);
    }
}
