<?php

namespace App\Exports;

use App\Models\PhieuXuatKho;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PhieuXuatKhoExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PhieuXuatKho::select(
            'ma_phieu',
            'ngay_xuat',
            'nhan_vien_id',
            'nguoi_nhan',
            'loai_phieu',
            'nha_cung_cap_id',
            'tong_tien',
            'ghi_chu',
            'trang_thai'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Mã Phiếu',
            'Ngày Xuất',
            'ID Nhân Viên',
            'Người Nhận',
            'Loại Phiếu',
            'ID Nhà Cung Cấp',
            'Tổng Tiền',
            'Ghi Chú',
            'Trạng Thái',
        ];
    }
}

