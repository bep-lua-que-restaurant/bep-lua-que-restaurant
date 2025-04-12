<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChucVuRequest extends FormRequest
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
        $id = $this->route('chuc_vu');
            return [
                'ten_chuc_vu' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('chuc_vus')->ignore($id)
                ],
                'mo_ta' => 'nullable|string',
            ];      
    }

    public function messages(): array
    {
        return [
            'ten_chuc_vu.required' => 'Tên dịch vụ không được để trống',
            'ten_chuc_vu.string' => 'Tên dịch vụ phải là chuỗi',
            'ten_chuc_vu.max' => 'Tên dịch vụ không được quá 255 ký tự',
            'ten_chuc_vu.unique' => 'Tên dịch vụ đã tồn tại',
            'mo_ta.string' => 'Mô tả phải là chuỗi'
         
        ];
    }
}
