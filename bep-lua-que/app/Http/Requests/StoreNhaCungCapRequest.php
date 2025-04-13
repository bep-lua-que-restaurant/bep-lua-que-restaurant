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
            'so_dien_thoai' => ['required', 'unique:nha_cung_caps', 'regex:/^(0|\+84)[0-9]{9}$/'],
            'email' => ['required', 'unique:nha_cung_caps', 'email', 'max:255'],
            'ghi_chu' => 'nullable|string',
            'hinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            'so_dien_thoai.required' => 'Số điện thoại không được để trống',
            'so_dien_thoai.unique' => 'Số điện thoại đã tồn tại đã tồn tại',
            'so_dien_thoai.regex' => 'Số điện thoại không đúng định dạng. Ví dụ: 0912345678 hoặc +84912345678',
            'email.required' => 'Email không được để trống',
            'email.unique' => 'Email đã tồn tại',
            'moTa.string' => 'Mô tả phải là chuỗi',
            'hinhAnh.image' => 'Hình ảnh phải là ảnh',
            'hinhAnh.max' => 'Hình ảnh không được quá 2048 KB'
        ];
    }
}
