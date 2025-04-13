<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCaLamRequest extends FormRequest
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
        $id = $this->route('ca_lam');
        return [
            'ten_ca' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ca_lams')->ignore($id)
            ],
            'mo_ta' => 'nullable|string',
            'gio_bat_dau' => 'required',
            'gio_ket_thuc' => 'required',
    
        ];
    }

    public function messages(): array
    {
        return [
            'ten_ca.required' => 'Ca làm không được để trống',
            'ten_ca.string' => 'Ca làm phải là chuỗi',
            'ten_ca.max' => 'Ca làm không được quá 255 ký tự',
            'ten_ca.unique' => 'Ca làm đã tồn tại',
            'mo_ta.string' => 'Mô tả phải là chuỗi',
            'gio_bat_dau.required' => 'Giờ bắt đầu không được để trống',
            'gio_ket_thuc.required' => 'Giờ kết thúc không được để trống',
        ];
    }
}
