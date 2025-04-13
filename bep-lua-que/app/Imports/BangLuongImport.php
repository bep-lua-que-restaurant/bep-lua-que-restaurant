<?php

namespace App\Imports;

use App\Models\BangTinhLuong;
use App\Models\NhanVien;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BangLuongImport implements ToModel, WithHeadingRow
{

   

    public function headingRow(): int
    {
        return 2; // Bắt đầu từ dòng 2
    }

   



public function model(array $row)
{
    // Lấy tháng và năm từ ngày hiện tại
    $thangNam = Carbon::now()->format('Y-m-01');  // Định dạng 'YYYY-MM-01' (ngày đầu tháng)

    // Tìm nhân viên dựa trên tên nhân viên
    $nhanVien = NhanVien::where('ho_ten', $row['ten_nhan_vien'])->first();

    // Kiểm tra nếu tìm thấy nhân viên, nếu không có nhân viên, có thể xử lý theo ý muốn
    if (!$nhanVien) {
        // Xử lý khi không tìm thấy nhân viên (ví dụ: bỏ qua bản ghi hoặc trả về lỗi)
        return null;
    }

    return new BangTinhLuong([
        'nhan_vien_id' => $nhanVien->id,  // Lưu ID nhân viên
        'so_ca_lam' => $row['so_ca_lam'] ?? '',
        'tong_luong' => $row['tong_luong'] ?? '',
        'ghi_chu' => $row['ghi_chu'] ?? null,
        'thang_nam' => $thangNam,  // Thêm giá trị thang_nam với định dạng hợp lệ
        'created_at' => $row['ngay_tao'] ?? now(),
        'updated_at' => $row['ngay_cap_nhat'] ?? now(),
    ]);
}


    
    
    
    
}


