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

            // Validate nguyên liệu món ăn
            'nguyen_lieu_id' => 'required|array|min:1',
            'nguyen_lieu_id.*' => 'exists:nguyen_lieus,id', // Mỗi nguyên liệu phải tồn tại trong bảng nguyen_lieus
            'so_luong' => 'required|array|min:1',
            'so_luong.*' => 'numeric|min:0.01', // Số lượng phải là số dương
            'don_vi_tinh' => 'required|array|min:1',
            'don_vi_tinh.*' => 'string|max:50', // Đơn vị tính phải là chuỗi không quá 50 ký tự
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

            // Thông báo lỗi cho nguyên liệu món ăn
            'nguyen_lieu_id.required' => 'Vui lòng chọn ít nhất một nguyên liệu.',
            'nguyen_lieu_id.*.exists' => 'Nguyên liệu không hợp lệ.',
            'so_luong.required' => 'Số lượng nguyên liệu không được để trống.',
            'so_luong.*.numeric' => 'Số lượng nguyên liệu phải là số.',
            'so_luong.*.min' => 'Số lượng nguyên liệu phải lớn hơn 0.',
            'don_vi_tinh.required' => 'Đơn vị tính không được để trống.',
            'don_vi_tinh.*.string' => 'Đơn vị tính phải là một chuỗi.',
            'don_vi_tinh.*.max' => 'Đơn vị tính không được quá 50 ký tự.',
        ];
    }
}
