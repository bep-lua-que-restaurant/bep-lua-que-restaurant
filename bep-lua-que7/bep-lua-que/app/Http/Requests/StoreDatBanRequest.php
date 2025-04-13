<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDatBanRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép tất cả người dùng gửi request
    }

    public function rules()
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|digits_between:10,11',
            'customer_email' => 'nullable|email|max:255',
            'selectedIds' => 'required|array|min:1',
            'selectedIds.*' => 'exists:ban_ans,id',
            'thoi_gian_den' => ['required', 'date', 'after_or_equal:today'], // ✅ Kiểm tra ngày không được là ngày trong quá khứ
            //'gio_du_kien' => ['required', 'date_format:H:i:s'], // ✅ Sử dụng 'H:i' thay vì 'H:i:s'
            'num_people' => ['required', 'integer', 'min:1'],
        ];
    }

    // public function prepareForValidation()
    // {
    //     if ($this->has('gio_du_kien_gio') && $this->has('gio_du_kien_phut')) {
    //         $this->merge([
    //             'gio_du_kien' => sprintf('%02d:%02d', $this->gio_du_kien_gio, $this->gio_du_kien_phut) // Định dạng HH:MM
    //         ]);
    //     }
    // }

    public function messages()
    {
        return [
            'customer_name.required' => 'Họ và tên là bắt buộc.',
            'customer_name.string' => 'Họ và tên phải là chuỗi ký tự.',
            'customer_name.max' => 'Họ và tên không được vượt quá 255 ký tự.',

            'customer_phone.required' => 'Số điện thoại là bắt buộc.',
            'customer_phone.digits_between' => 'Số điện thoại phải có từ 10 đến 11 chữ số.',

            'customer_email.email' => 'Email phải là định dạng hợp lệ.',
            'customer_email.max' => 'Email không được vượt quá 255 ký tự.',

            'thoi_gian_den.required' => 'Thời gian đến là bắt buộc.',
            'thoi_gian_den.date' => 'Thời gian đến phải là một ngày hợp lệ.',
            'thoi_gian_den.after_or_equal' => 'Thời gian đến không được sớm hơn ngày hiện tại.',

            'gio_du_kien.required' => 'Giờ dự kiến sử dụng là bắt buộc.',
            'gio_du_kien.date_format' => 'Giờ dự kiến sử dụng phải có định dạng HH:MM.',

            'num_people.required' => 'Số người là bắt buộc.',
            'num_people.integer' => 'Số người phải là một số nguyên.',
            'num_people.min' => 'Số người phải lớn hơn hoặc bằng 1.',

            'selectedIds.required' => 'Bàn ăn là bắt buộc.',
            'selectedIds.array' => 'Bàn ăn phải là một mảng.',
            'selectedIds.min' => 'Cần chọn ít nhất một bàn ăn.',
            'selectedIds.*.exists' => 'Bàn ăn không hợp lệ.',
        ];
    }
}
