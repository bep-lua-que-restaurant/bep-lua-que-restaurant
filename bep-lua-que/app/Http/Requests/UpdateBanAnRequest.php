<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBanAnRequest extends FormRequest
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
            'ten_ban' => ['required', 'string', 'max:20'],
            'so_ghe' => ['required', 'integer', 'min:1', 'max:10'],
            'mo_ta' => ['nullable', 'string', 'max:500'],
        ];
    }


    public function messages(): array
    {
        return [
            'ten_ban.required' => 'Tên bàn ăn không được để trống.',
            'ten_ban.string' => 'Tên bàn ăn phải là một chuỗi ký tự.',
            'ten_ban.max' => 'Tên bàn ăn không được vượt quá 20 ký tự.',

            'so_ghe.required' => 'Số ghế không được để trống.',
            'so_ghe.integer' => 'Số ghế phải là một số nguyên.',
            'so_ghe.min' => 'Số ghế phải lớn hơn hoặc bằng 1.',
            'so_ghe.max' => 'Số ghế phải nhỏ hơn hoặc bằng 10.',


            'mo_ta.string' => 'Mô tả phải là một chuỗi ký tự.',
            'mo_ta.max' => 'Mô tả không được dài quá 500 ký tự.',

            'vi_tri.required' => 'Vị trí bàn không được để trống.',
            'vi_tri.string' => 'Vị trí bàn phải là một chuỗi ký tự.',
            'vi_tri.max' => 'Vị trí bàn không được vượt quá 255 ký tự.',
        ];
    }
}
