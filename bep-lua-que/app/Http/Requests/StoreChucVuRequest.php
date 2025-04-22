<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChucVuRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép tất cả người dùng
    }

    public function rules()
    {
        return [
            'ten_chuc_vu' => 'required|string|max:255|unique:chuc_vus,ten_chuc_vu',
        ];
    }

    public function messages()
    {
        return [
            'ten_chuc_vu.required' => 'Tên chức vụ là bắt buộc.',
            'ten_chuc_vu.string' => 'Tên chức vụ phải là chuỗi.',
            'ten_chuc_vu.max' => 'Tên chức vụ không được vượt quá 255 ký tự.',
            'ten_chuc_vu.unique' => 'Tên chức vụ đã tồn tại.',
        ];
    }
}