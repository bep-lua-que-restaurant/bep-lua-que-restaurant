<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMonAnRequest extends FormRequest
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
    public function rules()
    {
        return [
            'ten' => 'required|string|max:255|unique:mon_ans,ten',
            'danh_muc_mon_an_id' => 'required|exists:danh_muc_mon_ans,id',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'trang_thai' => 'nullable|in:dang_ban,het_hang,ngung_ban',
            'hinh_anh.*' => 'nullable|image|max:2048',

           
        ];
    }
    
    public function messages()
    {
        return [
            'ten.required' => 'Tên món ăn không được để trống.',
            'ten.unique' => 'Tên món ăn đã tồn tại.',
            'danh_muc_mon_an_id.required' => 'Danh mục món ăn không được để trống.',
            'danh_muc_mon_an_id.exists' => 'Danh mục món ăn không hợp lệ.',
            'gia.required' => 'Giá món không được để trống.',
            'gia.numeric' => 'Giá phải là một số.',
            'gia.min' => 'Giá phải lớn hơn hoặc bằng 0.',
            'trang_thai.in' => 'Trạng thái món ăn không hợp lệ.',
            'hinh_anh.*.image' => 'Tệp tải lên phải là hình ảnh.',
            'hinh_anh.*.max' => 'Hình ảnh không được vượt quá 2MB.',

           
        ];
    }
}
