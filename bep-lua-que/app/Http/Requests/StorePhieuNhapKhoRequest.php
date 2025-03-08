<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhieuNhapKhoRequest extends FormRequest
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

            // Bắt buộc có ít nhất một nguyên liệu
            'nguyen_lieu' => 'required|array|min:1',
            'nguyen_lieu.*.trang_thai' => 'required|in:Đạt,Không đạt,Cần kiểm tra',
            'nguyen_lieu.*.loai_nguyen_lieu_id' => 'required|exists:loai_nguyen_lieus,id',
            'nguyen_lieu.*.ten_nguyen_lieu' => 'required|string|max:255',
            'nguyen_lieu.*.don_vi_tinh' => 'required|string|max:255',
            'nguyen_lieu.*.don_vi_nhap' => 'required|string|max:255',
            'nguyen_lieu.*.he_so_quy_doi' => 'required|numeric|min:1',

            'nguyen_lieu.*.so_luong' => 'required|integer|min:1',
            'nguyen_lieu.*.don_gia' => 'required|numeric|min:0',
            'nguyen_lieu.*.han_su_dung' => 'required|date|after_or_equal:today',
        ];
    }

    public function messages()
    {
        return [
            'nhan_vien_id.required' => 'Nhân viên nhập kho là bắt buộc.',
            'nha_cung_cap_id.required' => 'Nhà cung cấp là bắt buộc.',
            'ngay_nhap.required' => 'Ngày nhập kho là bắt buộc.',

            'nguyen_lieu.required' => 'Bạn phải nhập ít nhất một nguyên liệu.',
            'nguyen_lieu.array' => 'Chi tiết nguyên liệu phải là một mảng.',
            'nguyen_lieu.min' => 'Bạn phải nhập ít nhất một nguyên liệu.',
            'nguyen_lieu.*.trang_thai.required' => 'Không được để trống trạng thái nguyên liệu',

            'nguyen_lieu.*.ten_nguyen_lieu.required' => 'Tên nguyên liệu không được để trống.',
            'nguyen_lieu.*.loai_nguyen_lieu_id.required' => 'Loại nguyên liệu không được để trống.',
            'nguyen_lieu.*.so_luong.min' => 'Số lượng nguyên liệu phải lớn hơn 0.',
            'nguyen_lieu.*.so_luong.required' => 'Số lượng nguyên liệu không được để trống.',
            'nguyen_lieu.*.don_vi_tinh.required' => 'Đơn vị tính không được để trống.',
            'nguyen_lieu.*.don_vi_nhap.required' => 'Đơn vị nhập không được để trống.',
            'nguyen_lieu.*.he_so_quy_doi.required' => 'Hệ số quy đổi không được để trống',
            'nguyen_lieu.*.don_gia.min' => 'Giá nhập phải lớn hơn hoặc bằng 0.',
            'nguyen_lieu.*.don_gia.required' => 'Giá nhập không được để trống.',
            'nguyen_lieu.*.han_su_dung.required' => 'Hạn sử dụng không được để trống.',
            'nguyen_lieu.*.han_su_dung.after_or_equal' => 'Hạn sử dụng không được trước ngày hôm nay.',
        ];
    }
}
