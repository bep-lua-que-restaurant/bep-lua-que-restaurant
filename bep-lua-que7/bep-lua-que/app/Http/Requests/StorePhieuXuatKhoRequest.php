<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhieuXuatKhoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ngay_xuat' => 'required|date',
            'nguoi_nhan' => 'nullable|string|max:255',
            'loai_phieu' => 'required|in:xuat_bep,xuat_tra_hang,xuat_huy',
            'nha_cung_cap_id' => 'nullable|exists:nha_cung_caps,id',
            'nhan_vien_id' => 'nullable|exists:nhan_viens,id',
            'ghi_chu' => 'nullable|string',

            // Chi tiết phiếu
            'nguyen_lieu_id' => 'required|array|min:1',
            'nguyen_lieu_id.*' => 'required|exists:nguyen_lieus,id',
            'don_vi_xuat.*' => 'required|string',
            'he_so_quy_doi.*' => 'nullable|numeric|min:0',
            'so_luong.*' => 'required|numeric|min:0.01',
            'don_gia.*' => 'nullable|numeric|min:0',
            'ghi_chu_chi_tiet.*' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'ngay_xuat.required' => 'Vui lòng chọn ngày xuất kho.',
            'loai_phieu.required' => 'Vui lòng chọn loại phiếu.',
            'nguyen_lieu_id.required' => 'Vui lòng chọn ít nhất một nguyên liệu.',
            'nguyen_lieu_id.*.required' => 'Vui lòng chọn nguyên liệu.',
            'so_luong.*.required' => 'Vui lòng nhập số lượng.',
            'so_luong.*.min' => 'Số lượng phải lớn hơn 0.',
        ];
    }
}

