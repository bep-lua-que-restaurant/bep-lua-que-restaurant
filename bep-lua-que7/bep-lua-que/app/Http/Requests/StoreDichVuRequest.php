<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDichVuRequest extends FormRequest
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
            'ten_dich_vu' => ['required', 'unique:dich_vus', 'string', 'max:255'],
            'mo_ta' => ['string','nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'ten_dich_vu.required' => 'Tên dịch vụ không được để trống',
            'ten_dich_vu.unique' => 'Tên dịch vụ đã tồn tại',
            'ten_dich_vu.string' => 'Tên dịch vụ phải là chuỗi',
            'ten_dich_vu.max' => 'Tên dịch vụ không được quá 255 ký tự',
            'mo_ta.string' => 'Mô tả phải là chuỗi',
        ];
    }
}
