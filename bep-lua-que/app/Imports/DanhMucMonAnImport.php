<?php

namespace App\Imports;

use App\Models\DanhMucMonAn;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class DanhMucMonAnImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Kiểm tra và lấy dữ liệu từ các cột trong Excel
        $ten = $row['Tên'] ?? null;  // Lấy giá trị từ cột 'Tên'
        $ngayTao = $row['Ngày tạo'] ?? null;  // Lấy giá trị từ cột 'Ngày tạo'
        $moTa = $row['Mô tả'] ?? null;  // Lấy giá trị từ cột 'Mô tả' (có thể null)
        $trangThaiKinhDoanh = $row['Trạng thái kinh doanh'] ?? null;  // Lấy giá trị từ cột 'Trạng thái kinh doanh'

        // Ngày tạo sẽ là ngày hiện tại nếu 'Ngày tạo' không có dữ liệu
        $createdAt = $ngayTao ? Carbon::createFromFormat('d/m/Y', $ngayTao)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        // Xóa mềm mặc định là null
        $deletedAt = null;

        // Xử lý trạng thái kinh doanh (nếu cần)
        $deletedAt = strtolower($trangThaiKinhDoanh) === 'ngừng kinh doanh' ? now() : null;

        return new DanhMucMonAn([
            'ten' => $ten,  // Lấy tên từ cột 'Tên' trong Excel
            'mo_ta' => $moTa,  // Mô tả từ cột 'Mô tả', có thể là null
            'created_at' => $createdAt,  // Ngày tạo lấy từ cột 'Ngày tạo' hoặc là ngày hiện tại
            'deleted_at' => $deletedAt,  // Trạng thái xóa mềm nếu 'Trạng thái kinh doanh' là 'ngừng kinh doanh'
        ]);
    }
}
