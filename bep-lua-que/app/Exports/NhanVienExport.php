<?php

namespace App\Exports;

use App\Models\NhanVien;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;

class NhanVienExport implements FromCollection, WithHeadings, WithEvents, WithMapping
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return NhanVien::withTrashed()->select(
            'id', 'chuc_vu_id', 'ma_nhan_vien', 'ho_ten', 'email', 
            'so_dien_thoai', 'password', 'dia_chi', 'hinh_anh', 'gioi_tinh', 
            'ngay_sinh', 'ngay_vao_lam', 'deleted_at', 'created_at', 'updated_at', 'trang_thai'
        )->get();
    }

    public function headings(): array
    {
        return [
            ['DANH SÁCH NHÂN VIÊN'], // Tiêu đề lớn
            [
                'ID', 'Chức vụ', 'Mã nhân viên', 'Họ tên', 'Email', 'Số điện thoại', 
                'Mật khẩu', 'Địa chỉ', 'Hình ảnh', 'Giới tính', 'Ngày sinh', 
                'Ngày vào làm', 'Trạng thái', 'Ngày tạo', 'Ngày cập nhật'
            ], // Headers
        ];
    }

    public function map($row): array
{
    return [
        $row->id,
        $row->chucVu && !is_int($row->chucVu) ? $row->chucVu->ten_chuc_vu : '',
        $row->ma_nhan_vien,
        $row->ho_ten,
        $row->email,
        $row->so_dien_thoai,
        $row->password,
        $row->dia_chi,
        $row->hinh_anh,
        $row->gioi_tinh ? 'Nam' : 'Nữ',
        $row->ngay_sinh ? Carbon::parse($row->ngay_sinh)->format('d/m/Y') : '',
        $row->ngay_vao_lam ? Carbon::parse($row->ngay_vao_lam)->format('d/m/Y') : '',
        $row->deleted_at ? 'Ngừng làm việc' : 'Đang làm việc',
        $row->created_at ? Carbon::parse($row->created_at)->format('d/m/Y H:i') : '',
        $row->updated_at ? Carbon::parse($row->updated_at)->format('d/m/Y H:i') : '',
    ];
}

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Gộp ô cho tiêu đề lớn
                $sheet->mergeCells('A1:O1');
                $sheet->setCellValue('A1', 'DANH SÁCH NHÂN VIÊN');

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

                // Định dạng tiêu đề cột
                $sheet->getStyle('A2:O2')->applyFromArray([
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
                foreach (range('A', 'O') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
