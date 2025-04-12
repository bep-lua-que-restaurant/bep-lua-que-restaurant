<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoaiNguyenLieuRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ten_loai' => 'required|string|max:255|unique:loai_nguyen_lieus,ten_loai',
            'ghi_chu'  => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'ten_loai.required' => 'Tên loại nguyên liệu không được để trống.',
            'ten_loai.unique'   => 'Tên loại nguyên liệu đã tồn tại.',
            'ten_loai.max'      => 'Tên loại nguyên liệu tối đa 255 ký tự.',
            'ghi_chu.max'       => 'Ghi chú tối đa 500 ký tự.',
        ];
    }
}