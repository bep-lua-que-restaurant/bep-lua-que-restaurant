<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCaLamRequest extends FormRequest
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
            'ten_ca' => ['required', 'string', 'max:255','unique:ca_lams,ten_ca'],
            'gio_bat_dau' => ['required', 'date_format:H:i'],
            'gio_ket_thuc' => ['required', 'date_format:H:i'],
            'mo_ta' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'ten_ca.required' => 'Tên ca làm không được để trống',
            'ten_ca.unique' => 'Tên ca làm đã tồn tại',
            'ten_ca.string' => 'Tên ca làm phải là chuỗi',
            'ten_ca.max' => 'Tên ca làm không được vượt quá 255 ký tự',
            'gio_bat_dau.required' => 'Giờ bắt đầu không được để trống',
            'gio_bat_dau.date_format' => 'Giờ bắt đầu không đúng định dạng',
            'gio_ket_thuc.required' => 'Giờ kết thúc không được để trống',
            'gio_ket_thuc.date_format' => 'Giờ kết thúc không đúng định dạng',
            'mo_ta.string' => 'Mô tả phải là chuỗi',
        ];
    }
}
