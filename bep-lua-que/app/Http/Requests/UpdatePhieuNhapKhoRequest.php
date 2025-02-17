<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhieuNhapKhoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

            'nhan_vien_id' => 'required|exists:nhan_viens,id',
            'nha_cung_cap_id' => 'required|exists:nha_cung_caps,id',
            'ngay_nhap' => 'required|date',
            'ghi_chu' => 'nullable|string|max:500',
            'chi_tiet' => 'required|array|min:1',
            'chi_tiet.*.loai_nguyen_lieu_id' => 'required|exists:loai_nguyen_lieus,id',
            'chi_tiet.*.ten_nguyen_lieu' => 'required|string|max:255',
            'chi_tiet.*.so_luong' => 'required|integer|min:1',
            'chi_tiet.*.don_gia' => 'required|numeric|min:0',
            'chi_tiet.*.han_su_dung' => 'nullable|date|after_or_equal:today',

        ];
    }
}
