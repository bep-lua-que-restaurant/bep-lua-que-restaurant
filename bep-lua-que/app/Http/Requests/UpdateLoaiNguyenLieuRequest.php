<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLoaiNguyenLieuRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép mọi user sử dụng request này, có thể thay đổi theo logic auth của bạn
    }

    public function rules()
    {
        $id = $this->route('loai_nguyen_lieu')?->id; // Đúng tên tham số trong route

        return [
            'ma_loai'  => 'required|string|max:50|unique:loai_nguyen_lieus,ma_loai,' . ($id ?? 'NULL') . ',id',
            'ten_loai' => 'required|string|max:255|unique:loai_nguyen_lieus,ten_loai,' . ($id ?? 'NULL') . ',id',
            'mo_ta'    => 'nullable|string',
        ];
    }



    public function messages()
    {
        return [
            'ma_loai.required'  => 'Mã loại nguyên liệu không được để trống.',
            'ma_loai.unique'    => 'Mã loại nguyên liệu đã tồn tại.',
            'ma_loai.max'       => 'Mã loại nguyên liệu tối đa 50 ký tự.',

            'ten_loai.required' => 'Tên loại nguyên liệu không được để trống.',
            'ten_loai.unique'   => 'Tên loại nguyên liệu đã tồn tại.',
            'ten_loai.max'      => 'Tên loại nguyên liệu tối đa 255 ký tự.',

            'mo_ta.string'      => 'Mô tả phải là một chuỗi ký tự hợp lệ.',
        ];
    }
}
