<?php

namespace App\Exports;

use App\Models\DanhMucMonAn;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class DanhMucMonAnExport implements FromCollection, WithStyles, WithHeadings, WithTitle, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    use Exportable;
    public function collection()
    {
        return DanhMucMonAn::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tên món ăn',
            'Trạng thái',
            'Ngày tạo',
            'Ngày cập nhật',
        ];
    }

    public function title(): string
    {
        return 'Danh mục món ăn';
    }

    // Định dạng kiểu dáng cho bảng
    public function styles($sheet)
    {
        return [
            // Định dạng cho các header
            1    => [
                'font' => ['bold' => true, 'size' => 14],  // Định dạng chữ đậm và cỡ chữ 14 cho header
                'alignment' => ['horizontal' => 'center'], // Canh giữa các header
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFCC00']], // Màu nền vàng cho header
            ],
            // Định dạng các cột (ví dụ cột ID là cột 1)
            2    => [
                'font' => ['italic' => true], // Chữ nghiêng cho cột Tên món ăn
            ],
        ];
    }

    // Định dạng dữ liệu của cột (ví dụ cột ngày tháng)
    public function columnFormats(): array
    {
        return [
            'D' => 'yyyy-mm-dd',  // Định dạng ngày tháng
            'E' => 'yyyy-mm-dd',
        ];
    }

    // Thêm các sự kiện vào file Excel
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                // Đặt đường viền cho tất cả các ô dữ liệu
                $sheet->getStyle('A1:E' . $sheet->getHighestRow())
                    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}
