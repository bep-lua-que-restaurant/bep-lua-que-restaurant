<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateComBoRequest extends FormRequest
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
        $id = $this->route('com_bo');
        return [
            'ten' => [
                'required',
                'string',
                'max:255',
                Rule::unique('com_bos')->ignore($id)
            ],
            'mo_ta' => 'nullable|string',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'ten.required' => 'Tên combo không được để trống',
            'ten.string' => 'Tên combo phải là chuỗi',
            'ten.max' => 'Tên combo không được quá 255 ký tự',
            'ten.unique' => 'Tên combo đã tồn tại',
            'mo_ta.string' => 'Mô tả phải là chuỗi',
            'hinh_anh.image' => 'Hình ảnh phải là ảnh',
            'hinh_anh.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif, svg',
            'hinh_anh.max' => 'Hình ảnh không được quá 2048 KB',
        ];
    }
}
