<?php

namespace App\Exports;

use App\Models\CaLamNhanVien;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CaLamNhanVienExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return CaLamNhanVien::with(['caLam', 'nhanVien'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nhân Viên',
            'Ca Làm',
            'Ngày Làm',
            'Giờ Bắt Đầu',
            'Giờ Kết Thúc',
            'Trạng Thái',
            'Ngày Tạo'
        ];
    }

    public function map($caLamNhanVien): array
    {
        return [
            $caLamNhanVien->id,
            $caLamNhanVien->nhanVien->ho_ten ?? 'N/A',
            $caLamNhanVien->caLam->ten_ca ?? 'N/A',
            $caLamNhanVien->ngay_lam,
            $caLamNhanVien->gio_bat_dau,
            $caLamNhanVien->gio_ket_thuc,
            $caLamNhanVien->trang_thai,
            $caLamNhanVien->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
    