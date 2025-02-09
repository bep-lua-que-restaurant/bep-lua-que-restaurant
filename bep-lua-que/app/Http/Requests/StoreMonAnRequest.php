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
            'hinh_anh.*' => 'nullable|image|max:2048'
        ];
    }
    
    public function messages()
    {
        return [
            'danh_muc_mon_an_id' => 'Danh mục món ăn ',
            'ten.required' => ' Tên món ăn Không được để trống',
            'gia.required' => ' Giá món Không được để trống',
            'gia.numeric'=> 'Giá nhập vào phải là số',
            'trang_thai' => 'Trạng thái món',
            'hinh_anhs' => 'Hình ảnh món',

        ];
    }
}
