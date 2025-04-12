<?php

namespace App\Exports;

use App\Models\PhieuNhapKho;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DanhSachPhieuNhapExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return PhieuNhapKho::with(['nhaCungCap', 'nhanVien'])->get();
    }

    public function map($phieu): array
    {
        return [
            $phieu->ma_phieu,
            $phieu->ngay_nhap,
            $phieu->nhaCungCap->ten_nha_cung_cap ?? '',
            $phieu->nhanVien->ho_ten ?? '',
            number_format($phieu->tong_tien, 0, ',', '.'),
            $phieu->ghi_chu,
            $this->formatTrangThai($phieu->trang_thai),
        ];
    }

    public function headings(): array
    {
        return [
            'Mã phiếu',
            'Ngày nhập',
            'Nhà cung cấp',
            'Nhân viên',
            'Tổng tiền',
            'Ghi chú',
            'Trạng thái',
        ];
    }

    private function formatTrangThai($tt)
    {
        return match ($tt) {
            'cho_duyet' => 'Chờ duyệt',
            'da_duyet' => 'Đã duyệt',
            'da_huy' => 'Đã huỷ',
            default => 'Không xác định',
        };
    }
}

