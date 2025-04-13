<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePhieuNhapKhoRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có quyền gửi yêu cầu này hay không.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Quy tắc xác thực khi cập nhật.
     */
    public function rules()
{
    return [
        'ma_phieu' => [
            'required',
            'string',
            'max:255',
            Rule::unique('phieu_nhap_khos', 'ma_phieu')->ignore($this->phieu_nhap_kho)
        ],

        'nha_cung_cap_id' => 'required|exists:nha_cung_caps,id',
        'nhan_vien_id' => 'required|exists:nhan_viens,id',
        'ghi_chu' => 'nullable|string',

        // Chi tiết phiếu nhập
        'ten_nguyen_lieus' => 'required|array',
        'ten_nguyen_lieus.*' => 'required|string',

        'loai_nguyen_lieu_ids' => 'required|array',
        'loai_nguyen_lieu_ids.*' => 'exists:loai_nguyen_lieus,id',

        'don_vi_nhaps' => 'required|array',
        'don_vi_nhaps.*' => 'required|string',

        'don_vi_tons' => 'nullable|array',
        'don_vi_tons.*' => 'nullable|string',

        'so_luong_nhaps' => 'required|array',
        'so_luong_nhaps.*' => 'required|numeric|min:0.01',

        'he_so_quy_dois' => 'required|array',
        'he_so_quy_dois.*' => 'required|numeric|min:0.01',

        'don_gias' => 'required|array',
        'don_gias.*' => 'required|numeric|min:0',

        'ngay_san_xuats' => 'nullable|array',
        'ngay_san_xuats.*' => 'nullable|date',

        'ngay_het_hans' => 'nullable|array',
        'ngay_het_hans.*' => 'nullable|date|after_or_equal:ngay_san_xuats.*',

        'ghi_chus' => 'nullable|array',
        'ghi_chus.*' => 'nullable|string',

        // Duy trì validate cho chi tiết có ID
        'chi_tiet_ids' => 'nullable|array',
        'chi_tiet_ids.*' => 'exists:chi_tiet_phieu_nhap_khos,id',  // Chỉ kiểm tra nếu có ID chi tiết
    ];
}


    /**
     * Thông báo lỗi xác thực.
     */
    public function messages()
    {
        return [
            'ma_phieu.required' => 'Mã phiếu nhập kho là bắt buộc.',
            'ma_phieu.unique' => 'Mã phiếu nhập kho đã tồn tại.',
    
            'nha_cung_cap_id.required' => 'Nhà cung cấp là bắt buộc.',
            'nhan_vien_id.required' => 'Nhân viên là bắt buộc.',
    
            'ten_nguyen_lieus.required' => 'Tên nguyên liệu là bắt buộc.',
            'ten_nguyen_lieus.*.required' => 'Tên nguyên liệu không được để trống.',
    
            'loai_nguyen_lieu_ids.required' => 'Loại nguyên liệu là bắt buộc.',
            'loai_nguyen_lieu_ids.*.exists' => 'Loại nguyên liệu không tồn tại.',
    
            'don_vi_nhaps.required' => 'Đơn vị nhập là bắt buộc.',
            'don_vi_nhaps.*.required' => 'Đơn vị nhập không được để trống.',

            'don_vi_tons.*.nullable' => 'Đơn vị tồn có thể để trống.',
            'don_vi_tons.*.string' => 'Đơn vị tồn phải là chuỗi.',
    
            'so_luong_nhaps.required' => 'Số lượng nhập là bắt buộc.',
            'so_luong_nhaps.*.required' => 'Số lượng nhập không được để trống.',
            'so_luong_nhaps.*.min' => 'Số lượng nhập phải lớn hơn 0.',
    
            'he_so_quy_dois.required' => 'Hệ số quy đổi là bắt buộc.',
            'he_so_quy_dois.*.required' => 'Hệ số quy đổi không được để trống.',
            'he_so_quy_dois.*.min' => 'Hệ số quy đổi phải lớn hơn 0.',
    
            'don_gias.required' => 'Đơn giá là bắt buộc.',
            'don_gias.*.required' => 'Đơn giá không được để trống.',
            'don_gias.*.min' => 'Đơn giá phải lớn hơn 0.',
    
            'ngay_san_xuats.*.date' => 'Ngày sản xuất phải là ngày hợp lệ.',
            'ngay_het_hans.*.date' => 'Hạn sử dụng phải là ngày hợp lệ.',
            'ngay_het_hans.*.after_or_equal' => 'Hạn sử dụng phải sau hoặc bằng ngày sản xuất.',
    
            'ghi_chus.*.nullable' => 'Ghi chú có thể để trống.',
    
            // Thông báo lỗi cho chi tiết
            'chi_tiet_ids.*.exists' => 'Chi tiết phiếu nhập kho không tồn tại.',
        ];
    }
    
}
