<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDanhMucMonAnRequest extends FormRequest
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
            'ten' => ['required', 'unique:danh_muc_mon_ans', 'string', 'max:255'],
            'mo_ta' => ['string','nullable'],
            'hinh_anh' => ['image','nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'ten.required' => 'Tên danh mục không được để trống',
            'ten.unique' => 'Tên danh mục đã tồn tại',
            'ten.string' => 'Tên danh mục phải là chuỗi',
            'ten.max' => 'Tên danh mục không được quá 255 ký tự',
            'mo_ta.string' => 'Mô tả phải là chuỗi',
            'hinh_anh.image' => 'Hình ảnh phải là ảnh',
        ];
    }
}
