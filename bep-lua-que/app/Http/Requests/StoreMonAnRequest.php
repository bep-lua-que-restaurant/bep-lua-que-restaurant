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
            'thoi_gian_nau' => 'required|integer|min:1',
            'hinh_anh.*' => 'nullable|image|max:2048',

            // validate công thức
            'cong_thuc' => 'required|array|min:1',
            'cong_thuc.*.nguyen_lieu_id' => 'required|exists:nguyen_lieus,id',
            'cong_thuc.*.so_luong' => 'required|numeric|min:0.01',
            'cong_thuc.*.don_vi' => 'nullable|string|max:50',
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
            'thoi_gian_nau.required' => 'Thời gian nấu không được để trống.',
            'thoi_gian_nau.integer' => 'Thời gian nấu phải là một số nguyên.',
            'thoi_gian_nau.min' => 'Thời gian nấu phải lớn hơn 0.',
            'trang_thai.in' => 'Trạng thái món ăn không hợp lệ.',
            'hinh_anh.*.image' => 'Tệp tải lên phải là hình ảnh.',
            'hinh_anh.*.max' => 'Hình ảnh không được vượt quá 2MB.',

            // công thức
            'cong_thuc.required' => 'Cần ít nhất một dòng công thức.',
            'cong_thuc.*.nguyen_lieu_id.required' => 'Vui lòng chọn nguyên liệu cho từng dòng.',
            'cong_thuc.*.nguyen_lieu_id.exists' => 'Nguyên liệu không hợp lệ.',
            'cong_thuc.*.so_luong.required' => 'Vui lòng nhập số lượng cho từng nguyên liệu.',
            'cong_thuc.*.so_luong.numeric' => 'Số lượng phải là một số.',
            'cong_thuc.*.so_luong.min' => 'Số lượng phải lớn hơn 0.',
            'cong_thuc.*.don_vi.max' => 'Đơn vị không được dài quá 50 ký tự.',
        ];
    }
}
