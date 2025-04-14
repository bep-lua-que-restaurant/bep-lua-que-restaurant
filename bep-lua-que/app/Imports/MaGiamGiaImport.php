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
    public function headingRow(): int
    {
        return 2; // Bắt đầu từ dòng 2
    }
    public function model(array $row)
{
    return new MaGiamGia([
        'code'            => $row['ma'],
        'type'            => $row['loai'] === 'Phần trăm' ? 'percentage' : 'fixed',
        'value'           => $row['gia_tri'],
        'min_order_value' => $row['don_toi_thieu'], // Chỗ này hình như nhầm, min_order_value là giá trị, không phải ngày
        'start_date'      => $row['ngay_bat_dau'], // Không format nữa
        'end_date'        => $row['ngay_ket_thuc'], // Không format nữa
        'usage_limit'     => $row['so_luot_da_dung'],
        'created_at'      => $row['ngay_tao'], // Không format nữa
        'deleted_at'      => $row['trang_thai_hoat_dong'] === 'Ngừng hoạt động' ? now() : null,
    ]);
}

}
