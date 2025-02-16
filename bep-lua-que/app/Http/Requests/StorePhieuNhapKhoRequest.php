<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhieuNhapKhoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nhan_vien_id' => 'required|exists:nhan_viens,id',
            'nha_cung_cap_id' => 'required|exists:nha_cung_caps,id',
            'ngay_nhap' => 'required|date',
            'ghi_chu' => 'nullable|string|max:500',
            'nguyen_lieu' => 'nullable|array|min:1', // Không bắt buộc nhưng phải là mảng nếu tồn tại
            'nguyen_lieu.*.loai_nguyen_lieu_id' => 'nullable|exists:loai_nguyen_lieus,id',
            'nguyen_lieu.*.ten_nguyen_lieu' => 'nullable|string|max:255',
            'nguyen_lieu.*.so_luong' => 'nullable|integer|min:1',
            'nguyen_lieu.*.don_gia' => 'nullable|numeric|min:0',
            'nguyen_lieu.*.han_su_dung' => 'nullable|date|after_or_equal:today',
        ];
    }

    public function messages()
    {
        return [

            'nhan_vien_id.required' => 'Nhân viên nhập kho là bắt buộc.',
            'nha_cung_cap_id.required' => 'Nhà cung cấp là bắt buộc.',
            'ngay_nhap.required' => 'Ngày nhập kho là bắt buộc.',
            'nguyen_lieu.array' => 'Chi tiết nguyên liệu phải là một mảng.',
            'nguyen_lieu.*.so_luong.min' => 'Số lượng nguyên liệu phải lớn hơn 0.',
            'nguyen_lieu.*.don_gia.min' => 'Đơn giá phải lớn hơn hoặc bằng 0.',
        ];
    }
}
