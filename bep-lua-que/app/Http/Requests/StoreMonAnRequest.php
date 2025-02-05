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
    public function rules(): array
    {
        return [
            'danh_muc_mon_an_id' => 'required|exists:danh_muc_mon_ans,id',
            'ten' => 'required|string|max:255|unique:mon_ans,ten',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'trang_thai' => 'required|in:dang_ban,het_hang,ngung_ban',
            'hinh_anhs' => 'nullable|array',
            'hinh_anhs.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
    public function attributes(){
        return [
            'required'=>'Không được đê trống',
            'unique'=>'Đã tồn tại',
            'numeric'=>'Phải là số',
            'max:255'=>'quá số ký tự',
            'min:0'=> 'không được nhỏ hơn 0',
            'mimes:jpeg,png,jpg,gif'=>'không phải là tệp ảnh',
            'max:2048'=>'dữ liệu quá lớn',
        ];
    }
    public function messages(){
        return [
            'danh_muc_mon_an_id' => 'Danh mục món ăn ',
            'ten' => ' Tên món ăn',
            'gia' => ' Giá món',
            'trang_thai' => 'Trạng thái món',
            'hinh_anhs' => 'Hình ảnh món',
            
        ];
    }
}
