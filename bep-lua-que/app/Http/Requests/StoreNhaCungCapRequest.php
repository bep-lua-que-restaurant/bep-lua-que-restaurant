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
            'ten_nha_cung_cap' => ['required', 'unique:nha_cung_caps', 'string', 'max:255'],
            'dia_chi' => 'nullable|string',
            'so_dien_thoai' => 'required|string|max:20', // Đảm bảo có giá trị
            'email' => 'required|email|max:255',
            'ghi_chu' => 'nullable|string',
            'hinhAnh' => ['image','nullable'],
            'moTa' => ['string','nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'ten_nha_cung_cap.required' => 'Tên nhà cung cấp không được để trống',
            'ten_nha_cung_cap.unique' => 'Tên nhà cung cấp đã tồn tại',
            'ten_nha_cung_cap.string' => 'Tên nhà cung cấp phải là chuỗi',
            'ten_nha_cung_cap.max' => 'Tên nhà cung cấp không được quá 255 ký tự',
            'moTa.string' => 'Mô tả phải là chuỗi',
            'hinh_anh.image' => 'Hình ảnh phải là ảnh',
        ];
    }
}
