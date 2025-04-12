<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhieuNhapKhoRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có quyền gửi yêu cầu này hay không.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Đảm bảo chỉ người dùng có quyền có thể tạo phiếu nhập kho
    }

    /**
     * Lấy các quy tắc xác thực cho yêu cầu.
     *
     * @return array
     */
    public function rules()
{
    return [
        // Quy tắc xác thực cho bảng phiếu nhập kho
        'ma_phieu' => 'required|string|max:255|unique:phieu_nhap_khos,ma_phieu',
        
        'nha_cung_cap_id' => 'required|exists:nha_cung_caps,id',
        'nhan_vien_id' => 'required|exists:nhan_viens,id',
        'ghi_chu' => 'nullable|string',
        
        // Quy tắc xác thực cho bảng chi tiết phiếu nhập kho
        'ten_nguyen_lieus' => 'required|array',
        'ten_nguyen_lieus.*' => 'required|string', // Kiểm tra tên nguyên liệu
        
        'loai_nguyen_lieu_ids' => 'required|array',
        'loai_nguyen_lieu_ids.*' => 'exists:loai_nguyen_lieus,id',
        
        'don_vi_nhaps' => 'required|array',
        'don_vi_nhaps.*' => 'required|string',

        'don_vi_tons' => 'nullable|array',  
        'don_vi_tons.*' => 'nullable|string', // Kiểm tra đơn vị tồn
        
        'so_luong_nhaps' => 'required|array',
        'so_luong_nhaps.*' => 'required|numeric|min:0.01',
        
        'he_so_quy_dois' => 'required|array',
        'he_so_quy_dois.*' => 'required|numeric|min:0.01',
        
        'don_gias' => 'required|array',
        'don_gias.*' => 'required|numeric|min:0',

        'ngay_san_xuat' => 'nullable|date', // Trường ngày sản xuất
        'han_su_dung' => 'nullable|date|after_or_equal:ngay_san_xuat', // Trường hạn sử dụng

        'ghi_chus' => 'nullable|array',
        
    ];
}

/**
 * Các thông báo xác thực tùy chỉnh.
 *
 * @return array
 */
public function messages()
{
    return [
        // Thông báo cho bảng phiếu nhập kho
        'ma_phieu.required' => 'Mã phiếu nhập kho là bắt buộc.',
        'ma_phieu.unique' => 'Mã phiếu nhập kho đã tồn tại.',
      
        'nha_cung_cap_id.required' => 'Nhà cung cấp là bắt buộc.',
        'nhan_vien_id.required' => 'Nhân viên là bắt buộc.',
        
        // Thông báo cho bảng chi tiết phiếu nhập kho
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

        'ngay_san_xuat.date' => 'Ngày sản xuất phải là một ngày hợp lệ.',
        'han_su_dung.date' => 'Hạn sử dụng phải là một ngày hợp lệ.',
        'han_su_dung.after_or_equal' => 'Hạn sử dụng phải sau hoặc bằng ngày sản xuất.',

        'ghi_chus.array' => 'Ghi chú phải là một mảng.',
        'ghi_chus.*.nullable' => 'Ghi chú có thể để trống.',
    ];
}

}
