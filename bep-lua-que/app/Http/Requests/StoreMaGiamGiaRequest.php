<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaGiamGiaRequest extends FormRequest
{
    public function authorize()
    {
        // Nếu không có logic kiểm tra đặc biệt, trả về true
        return true;
    }

    public function rules()
    {
        return [
            'code'            => 'required|string|max:20|unique:ma_giam_gias,code',
            'type'            => 'required|in:percentage,fixed',
            'value'           => 'required|numeric|min:0.01',
            'min_order_value' => 'required|numeric|min:0',  // Bắt buộc phải nhập
            'start_date'      => 'required|date|after_or_equal:today',
            'end_date'        => 'required|date|after:start_date',
            'usage_limit'     => 'required|integer|min:0',  // Bắt buộc phải nhập
        ];
    }
    
    public function messages()
    {
        return [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.string' => 'Mã giảm giá phải là chuỗi ký tự.',
            'code.max' => 'Mã giảm giá không được vượt quá 20 ký tự.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
    
            'type.required' => 'Vui lòng chọn loại giảm giá.',
            'type.in' => 'Loại giảm giá không hợp lệ.',
    
            'value.required' => 'Vui lòng nhập giá trị giảm.',
            'value.numeric' => 'Giá trị giảm phải là số.',
            'value.min' => 'Giá trị giảm phải lớn hơn 0.',
    
            'min_order_value.required' => 'Vui lòng nhập giá trị đơn hàng tối thiểu.',
            'min_order_value.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_order_value.min' => 'Giá trị đơn hàng tối thiểu không được nhỏ hơn 0.',
    
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',
    
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
    
            'usage_limit.required' => 'Vui lòng nhập số lượt sử dụng.',
            'usage_limit.integer' => 'Số lượt sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Số lượt sử dụng không được nhỏ hơn 0.',
        ];
    }
    

}
