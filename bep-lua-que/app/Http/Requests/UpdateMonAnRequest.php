<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMonAnRequest extends FormRequest
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
        $monAnId = $this->route('mon_an');

        return [
            'danh_muc_mon_an_id' => ['required', 'exists:danh_muc_mon_ans,id'],

            'ten' => [
                'required',
                'max:255',
                Rule::unique('mon_ans', 'ten')->ignore($monAnId, 'id'),
            ],

            'gia' => ['required', 'numeric', 'min:0'],
            'thoi_gian_nau' => ['required', 'integer', 'min:0'], // ✅ thêm dòng này
            'trang_thai' => ['required', Rule::in(['dang_ban', 'het_hang', 'ngung_ban'])],
        ];
    }

    public function messages()
    {
        return [
            'danh_muc_mon_an_id.required' => 'Danh mục món ăn là bắt buộc.',
            'ten.required' => 'Tên món ăn là bắt buộc.',
            'ten.unique' => 'Tên món ăn đã tồn tại.',
            'gia.required' => 'Giá món ăn là bắt buộc.',
            'gia.numeric' => 'Giá món ăn phải là một số.',
            'gia.min' => 'Giá món ăn phải lớn hơn hoặc bằng 0.',
            'thoi_gian_nau.required' => 'Thời gian nấu là bắt buộc.',
            'thoi_gian_nau.integer' => 'Thời gian nấu phải là số nguyên.',
            'thoi_gian_nau.min' => 'Thời gian nấu phải lớn hơn hoặc bằng 0.',
            'trang_thai.required' => 'Trạng thái món ăn là bắt buộc.',
            'trang_thai.in' => 'Trạng thái món ăn không hợp lệ.',
        ];
    }
}
