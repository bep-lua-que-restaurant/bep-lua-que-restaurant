<?php

namespace App\Exports;

use App\Models\BangTinhLuong;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BangLuongExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return BangTinhLuong::with(['nhanVien:id,ho_ten'])
            ->whereMonth('thang_nam', $this->month)   // lọc theo tháng
            ->whereYear('thang_nam', $this->year)      // lọc theo năm
            ->select('id', 'nhan_vien_id', 'thang_nam', 'so_ca_lam', 'so_ngay_cong', 'tong_luong', 'ghi_chu', 'created_at', 'updated_at')
            ->get();
    }


    public function headings(): array
    {
        return [
            ['Bảng lương'], // Tiêu đề lớn
            ['ID', 'Tên nhân viên', 'Tháng năm', 'Số ca làm', 'Số ngày công', 'Tổng lương', 'Ghi chú', 'Ngày tạo', 'Ngày cập nhật'],
        ];
    }

    public function map($bangLuong): array
    {
        return [
            $bangLuong->id,
            $bangLuong->nhanVien->ho_ten ?? 'N/A',
            $bangLuong->thang_nam,
            $bangLuong->so_ca_lam,
            $bangLuong->so_ngay_cong,
            $bangLuong->tong_luong,
            $bangLuong->ghi_chu,
            $bangLuong->created_at ? $bangLuong->created_at->format('d/m/Y') : '',
            $bangLuong->updated_at ? $bangLuong->updated_at->format('d/m/Y') : '',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Gộp ô tiêu đề lớn
                $sheet->mergeCells('A1:I1');
                $sheet->setCellValue('A1', 'Bảng lương');

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

                // Định dạng hàng tiêu đề
                $sheet->getStyle('A2:I2')->applyFromArray([
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
                foreach (range('A', 'I') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
