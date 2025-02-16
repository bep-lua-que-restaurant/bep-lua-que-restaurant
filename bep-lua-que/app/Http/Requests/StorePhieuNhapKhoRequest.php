<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhieuNhapKhoRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép mọi user gửi request này
    }

    public function rules()
    {
        return [
            'nhan_vien_id' => 'required|exists:nhan_viens,id',
            'nha_cung_cap_id' => 'nullable|exists:nha_cung_caps,id',
            
            'ngay_nhap' => 'required|date',

            // Kiểm tra danh sách nguyên liệu
            'ten_nguyen_lieu' => 'required|array|min:1',
            'ten_nguyen_lieu.*' => 'required|string|max:255',

            'loai_hang_id' => 'required|array|min:1',
            'loai_hang_id.*' => 'required|exists:loai_nguyen_lieus,id',

            'don_vi_tinh' => 'required|array|min:1',
            'don_vi_tinh.*' => 'required|string|max:50',

            'so_luong' => 'required|array|min:1',
            'so_luong.*' => 'required|numeric|min:1',

            'gia_nhap' => 'required|array|min:1',
            'gia_nhap.*' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'nhan_vien_id.required' => 'Vui lòng chọn nhân viên nhập kho.',
            'nhan_vien_id.exists' => 'Nhân viên không hợp lệ.',

            'nha_cung_cap_id.exists' => 'Nhà cung cấp không hợp lệ.',

            'ngay_nhap.required' => 'Vui lòng chọn ngày nhập.',
            'ngay_nhap.date' => 'Ngày nhập không hợp lệ.',

            //  Kiểm tra danh sách nguyên liệu
            'ten_nguyen_lieu.required' => 'Vui lòng nhập ít nhất một nguyên liệu.',
            'ten_nguyen_lieu.*.required' => 'Tên nguyên liệu không được để trống.',
            'ten_nguyen_lieu.*.max' => 'Tên nguyên liệu không được quá 255 ký tự.',

            'loai_hang_id.required' => 'Vui lòng chọn loại hàng cho nguyên liệu.',
            'loai_hang_id.*.exists' => 'Loại hàng không hợp lệ.',

            'don_vi_tinh.required' => 'Vui lòng nhập đơn vị tính.',
            'don_vi_tinh.*.max' => 'Đơn vị tính không được quá 50 ký tự.',

            'so_luong.required' => 'Vui lòng nhập số lượng.',
            'so_luong.*.numeric' => 'Số lượng phải là số.',
            'so_luong.*.min' => 'Số lượng phải lớn hơn 0.',

            'gia_nhap.required' => 'Vui lòng nhập giá nhập.',
            'gia_nhap.*.numeric' => 'Giá nhập phải là số.',
            'gia_nhap.*.min' => 'Giá nhập không thể âm.',
        ];
    }
}
