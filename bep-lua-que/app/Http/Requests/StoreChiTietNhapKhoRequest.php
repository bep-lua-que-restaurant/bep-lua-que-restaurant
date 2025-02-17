<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChiTietNhapKhoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phieu_nhap_kho_id' => 'required|exists:phieu_nhap_khos,id',
            'nguyen_lieu_id' => 'required|exists:nguyen_lieus,id',
            'so_luong' => 'required|numeric|min:1',
            'gia_nhap' => 'required|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'phieu_nhap_kho_id.required' => 'Vui lòng chọn phiếu nhập kho.',
            'phieu_nhap_kho_id.exists' => 'Phiếu nhập kho không hợp lệ.',
            'nguyen_lieu_id.required' => 'Vui lòng chọn nguyên liệu.',
            'nguyen_lieu_id.exists' => 'Nguyên liệu không hợp lệ.',
            'so_luong.required' => 'Vui lòng nhập số lượng.',
            'so_luong.numeric' => 'Số lượng phải là số.',
            'so_luong.min' => 'Số lượng phải lớn hơn 0.',
            'gia_nhap.required' => 'Vui lòng nhập giá nhập.',
            'gia_nhap.numeric' => 'Giá nhập phải là số.',
            'gia_nhap.min' => 'Giá nhập không được nhỏ hơn 0.',
        ];
    }
}
