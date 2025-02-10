<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhieuNhapKhoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'trang_thai' => 'required|in:cho_duyet,da_duyet,hoan_thanh,huy'
        ];
    }

    public function messages()
    {
        return [
            'trang_thai.required' => 'Vui lòng chọn trạng thái phiếu nhập.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
