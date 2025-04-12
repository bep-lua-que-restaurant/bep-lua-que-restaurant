<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChucVuRequest extends FormRequest
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
            'ten_chuc_vu' => ['required', 'unique:chuc_vus', 'string', 'max:255'],
            'mo_ta' => ['string','nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'ten_chuc_vu.required' => 'Tên chức vụ không được để trống',
            'ten_chuc_vu.unique' => 'Tên chức vụ đã tồn tại',
            'ten_chuc_vu.string' => 'Tên chức vụ phải là chuỗi',
            'ten_chuc_vu.max' => 'Tên chức vụ không được quá 255 ký tự',
            'mo_ta.string' => 'Mô tả phải là chuỗi',
        ];
    }
}
