<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhongAnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'ten_phong_an' => ['required', 'unique:phong_ans', 'string', 'max:50'],
        ];
    }

    public function messages()
    {
        return [
            'ten_phong_an.required' => 'Tên bàn không được để trống.',
            'ten_phong_an.unique' => 'Tên bàn đã tồn tại, vui lòng chọn tên khác.',
            'ten_phong_an.string' => 'Tên bàn phải là một chuỗi ký tự.',
            'ten_phong_an.max' => 'Tên bàn không được vượt quá 50 ký tự.'
        ];
    }
}
