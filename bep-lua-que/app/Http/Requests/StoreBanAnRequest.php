<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBanAnRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'ten_ban' => ['required', 'unique:ban_ans', 'string', 'max:255'], //  Chỉnh lại bảng đúng
            'so_ghe' => ['required', 'integer', 'min:1'], //  Sửa lỗi chính tả và thêm ràng buộc min:1
            'mo_ta' => ['nullable', 'string', 'max:500'], //  Thêm max:500 để tránh nhập quá dài
            'vi_tri' => ['required', 'string', 'max:255'], //  Thêm validate cho vi_tri
        ];
    }

    public function messages(): array
    {
        return [
            // Thông báo lỗi cho trường "ten_ban"
            'ten_ban.required' => 'Tên bàn không được để trống.', // Bắt buộc nhập tên bàn
            'ten_ban.unique' => 'Tên bàn đã tồn tại, vui lòng chọn tên khác.', // Không được trùng tên bàn trong database
            'ten_ban.string' => 'Tên bàn phải là một chuỗi ký tự.', // Phải là dạng chuỗi
            'ten_ban.max' => 'Tên bàn không được vượt quá 255 ký tự.',

            // Thông báo lỗi cho trường "so_ghe"
            'so_ghe.required' => 'Số ghế không được để trống.',
            'so_ghe.integer' => 'Số ghế phải là một số nguyên.',
            'so_ghe.min' => 'Số ghế phải lớn hơn hoặc bằng 1.',
            'mo_ta.string' => 'Mô tả phải là một chuỗi ký tự.',
            'mo_ta.max' => 'Mô tả không được dài quá 500 ký tự.',
            'vi_tri.required' => 'Vị trí không được để trống.',
            'vi_tri.string' => 'Vị trí phải là một chuỗi ký tự.',
            'vi_tri.max' => 'Vị trí không được dài quá 255 ký tự.',
        ];
    }
}
