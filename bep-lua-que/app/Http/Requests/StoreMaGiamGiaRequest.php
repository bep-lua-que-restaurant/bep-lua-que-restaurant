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
            'min_order_value' => 'nullable|numeric|min:0',
            'start_date'      => 'required|date|after_or_equal:today',
            'end_date'        => 'required|date|after:start_date',
            'usage_limit'     => 'nullable|integer|min:0',
        ];
    }
}
