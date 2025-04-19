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
            'nhan_vien_id' => 'required|exists:nhan_viens,id',
            'loai_phieu' => 'required|in:xuat_bep,xuat_tra_hang,xuat_huy',

            'nguoi_nhan' => 'required_if:loai_phieu,xuat_bep|required_if:loai_phieu,xuat_huy|required_if:loai_phieu,xuat_tra_hang|string|max:255',

            // Fix ở đây:
            'nha_cung_cap_id' => 'required_if:loai_phieu,xuat_tra_hang|nullable|exists:nha_cung_caps,id',

            'nguyen_lieu_ids' => 'required|array|min:1',
            'nguyen_lieu_ids.*' => 'required|exists:nguyen_lieus,id',

            'loai_nguyen_lieu_ids' => 'required|array|min:1',
            'loai_nguyen_lieu_ids.*' => 'required|exists:loai_nguyen_lieus,id',

            'don_vi_xuats.*' => ['required', 'string', 'min:1', 'max:255', 'regex:/^[^\d]*$/'],

            'so_luong_xuats.*' => 'required|numeric|min:0.01',
            'don_gias.*' => 'nullable|numeric|min:0',
            'ghi_chus.*' => 'nullable|string|max:255',
        ];
    }



    public function messages(): array
    {
        return [
            'ngay_xuat.required' => 'Vui lòng chọn ngày xuất kho.',
            'loai_phieu.required' => 'Vui lòng chọn loại phiếu.',
            'loai_phieu.in' => 'Loại phiếu không hợp lệ.',

            'nguyen_lieu_ids.required' => 'Vui lòng chọn ít nhất một nguyên liệu.',
            'nguyen_lieu_ids.*.required' => 'Vui lòng chọn nguyên liệu.',
            'nguyen_lieu_ids.*.exists' => 'Nguyên liệu không tồn tại.',

            'loai_nguyen_lieu_ids.required' => 'Vui lòng chọn ít nhất một loại nguyên liệu.',
            'loai_nguyen_lieu_ids.*.required' => 'Vui lòng chọn loại nguyên liệu.',
            'loai_nguyen_lieu_ids.*.exists' => 'Loại nguyên liệu không tồn tại.',

            'don_vi_xuats.*.required' => 'Vui lòng nhập đơn vị xuất.',
            'don_vi_xuats.*.string' => 'Đơn vị xuất phải là một chuỗi.',
            'don_vi_xuats.*.min' => 'Đơn vị xuất phải có ít nhất 1 ký tự.',
            'don_vi_xuats.*.max' => 'Đơn vị xuất không được vượt quá 255 ký tự.',
            'don_vi_xuats.*.regex' => 'Đơn vị xuất không được chứa số.',

           

            'so_luong_xuats.*.required' => 'Vui lòng nhập số lượng.',
            'so_luong_xuats.*.min' => 'Số lượng phải lớn hơn 0.',

            'don_gias.*.min' => 'Đơn giá phải lớn hơn hoặc bằng 0.',
            'ghi_chus.*.string' => 'Ghi chú phải là một chuỗi.',
            'ghi_chus.*.max' => 'Ghi chú không được vượt quá 255 ký tự.',

            'nha_cung_cap_id.required_if' => 'Vui lòng chọn nhà cung cấp khi loại phiếu là xuất trả hàng.',
            'nha_cung_cap_id.exists' => 'Nhà cung cấp không tồn tại.',

            'nhan_vien_id.required' => 'Vui lòng chọn nhân viên.',
            'nhan_vien_id.exists' => 'Nhân viên không tồn tại.',

            'nguoi_nhan.required_if' => 'Vui lòng nhập tên người nhận khi loại phiếu là xuất bếp hoặc xuất huỷ.',
        ];
    }
}
