<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNguyenLieuRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ma_nguyen_lieu' => 'required|string|unique:nguyen_lieus,ma_nguyen_lieu',
            'ten_nguyen_lieu' => 'required|string|max:255',
            'don_vi_tinh' => 'required|string|max:50',
            'so_luong_ton' => 'numeric|min:0',
            'so_luong_ton_toi_thieu' => 'numeric|min:0',
            'so_luong_ton_toi_da' => 'numeric|min:0',
            'gia_nhap' => 'numeric|min:0',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mo_ta' => 'nullable|string',
        ];
    }
    public function attributes(){
        return[
            'required'=>'không được để trống',
            'unique'=>'đã tồn tại',
            'min'=>'không được nhỏ hơn 0',
            'numeric'=>'nhập vào phải là số',
        ];
    }
    public function messages(){
        return [
            'ma_nguyen_lieu' => 'Mã nguyên liệu',
            'ten_nguyen_lieu' => 'Tên nguyên liệu',
            'don_vi_tinh' => 'Đơn vị tính',
            'so_luong_ton' => 'Số lượng tồn',
            'so_luong_ton_toi_thieu' => 'Số lượng tồn tối thiểu',
            'so_luong_ton_toi_da' => 'Số lượng tồn tối đa',
            'gia_nhap' => 'Giá nhập',
            'hinh_anh' => 'Hình ảnh',
            'mo_ta' => 'Mô tả',
        ];
    }
}
