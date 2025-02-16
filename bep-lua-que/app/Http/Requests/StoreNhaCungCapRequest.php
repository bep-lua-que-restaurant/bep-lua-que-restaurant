<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNhaCungCapRequest extends FormRequest
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
            'ten' => ['required', 'unique:nha_cung_caps', 'string', 'max:255'],
            'moTa' => ['string','nullable'],
            'hinhAnh' => ['image','nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'ten.required' => 'Tên nhà cung cấp không được để trống',
            'ten.unique' => 'Tên nhà cung cấp đã tồn tại',
            'ten.string' => 'Tên nhà cung cấp phải là chuỗi',
            'ten.max' => 'Tên nhà cung cấp không được quá 255 ký tự',
            'moTa.string' => 'Mô tả phải là chuỗi',
            'hinh_anh.image' => 'Hình ảnh phải là ảnh',
        ];
    }
}
