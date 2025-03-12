<?php

namespace App\Exports;

use App\Models\CaLamNhanVien;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class CaLamNhanVienExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return CaLamNhanVien::with(['caLam', 'nhanVien'])->get();
    }

    public function headings(): array
    {
        return [
            ['Ca làm nhân viên'],
            ['ID',
            'Nhân Viên',
            'Ca Làm',
            'Ngày Làm',
            'Giờ Bắt Đầu',
            'Giờ Kết Thúc',
            'Trạng Thái',
            'Ngày Tạo']
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
            $caLamNhanVien->created_at ? $caLamNhanVien->created_at->format('Y-m-d H:i:s') : 'N/A',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Gộp ô cho tiêu đề lớn
                $sheet->mergeCells('A1:D1');
                $sheet->setCellValue('A1', 'Dịch vụ');

                // Định dạng tiêu đề lớn
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F81BD'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Định dạng heading
                $sheet->getStyle('A2:D2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFC000'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Tự động căn chỉnh độ rộng cột
                foreach (range('A', 'D') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
    