<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDichVuRequest extends FormRequest
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
        $id = $this->route('dich_vu');
            return [
                'ten_dich_vu' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('dich_vus')->ignore($id)
                ],
                'mo_ta' => 'nullable|string',
            ];      
    }

    public function messages(): array
    {
        return [
            'ten_dich_vu.required' => 'Tên dịch vụ không được để trống',
            'ten_dich_vu.string' => 'Tên dịch vụ phải là chuỗi',
            'ten_dich_vu.max' => 'Tên dịch vụ không được quá 255 ký tự',
            'ten_dich_vu.unique' => 'Tên dịch vụ đã tồn tại',
            'mo_ta.string' => 'Mô tả phải là chuỗi'
         
        ];
    }
}
