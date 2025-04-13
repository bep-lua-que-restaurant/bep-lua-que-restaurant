<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNhaCungCapRequest extends FormRequest
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
        $id = $this->route('nha_cung_cap');
        return [
            'ten_nha_cung_cap' => [
                'required',
                'string',
                'max:255',
                Rule::unique('nha_cung_caps')->ignore($id)
            ],
            'moTa' => 'nullable|string',
            'hinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_nha_cung_cap.required' => 'Tên nhà cung cấp không được để trống',
            'ten_nha_cung_cap.string' => 'Tên nhà cung cấp phải là chuỗi',
            'ten_nha_cung_cap.max' => 'Tên nhà cung cấp không được quá 255 ký tự',
            'ten_nha_cung_cap.unique' => 'Tên nhà cung cấp đã tồn tại',
            'moTa.string' => 'Mô tả phải là chuỗi',
            'hinhAnh.image' => 'Hình ảnh phải là ảnh',
            'hinhAnh.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif, svg',
            'hinhAnh.max' => 'Hình ảnh không được quá 2048 KB',
        ];
    }
}
